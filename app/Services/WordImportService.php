<?php

namespace App\Services;

use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\ListItemRun;
use PhpOffice\PhpWord\Element\ListItem;
use PhpOffice\PhpWord\Element\Link;
use PhpOffice\PhpWord\Element\Image;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Row;
use PhpOffice\PhpWord\Element\Cell;
use PhpOffice\PhpWord\Element\TextBreak;

class WordImportService
{
    protected $currentSubjectId;
    protected $questions = [];
    protected $currentQuestion = null;
    protected $currentType = 'pilihan_ganda';
    protected $currentState = null;

    public function import($filePath, $subjectId)
    {
        $this->currentSubjectId = $subjectId;
        $this->questions = [];
        $this->currentQuestion = null;
        $this->currentState = null;

        $phpWord = IOFactory::load($filePath);

        $sections = $phpWord->getSections();
        foreach ($sections as $section) {
            $elements = $section->getElements();
            foreach ($elements as $element) {
                $this->processElement($element);
            }
        }

        // Save last question
        if ($this->currentQuestion) {
            $this->questions[] = $this->currentQuestion;
        }

        return $this->saveToDatabase();
    }

    protected function processElement($element)
    {
        $text = trim($this->getElementText($element));
        $html = $this->getElementHtml($element);

        // If divider is encountered, save the current question and reset
        if (preg_match('/^---/i', $text)) {
            if ($this->currentQuestion) {
                $this->questions[] = $this->currentQuestion;
                $this->currentQuestion = null;
                $this->currentState = null;
            }
            return;
        }

        // A new question block starts if we see a header marker AND the current question already has content or options
        $isQuestionHeaderMarker = preg_match('/^\[(TIPE|POIN|KESULITAN|SOAL)\]/i', $text);
        if ($isQuestionHeaderMarker && $this->currentQuestion) {
            if (!empty($this->currentQuestion['content']) || !empty($this->currentQuestion['options'])) {
                $this->questions[] = $this->currentQuestion;
                $this->currentQuestion = null;
                $this->currentState = null;
            }
        }

        // Initialize question if null
        if (!$this->currentQuestion) {
            $this->currentQuestion = [
                'subject_id' => $this->currentSubjectId,
                'content' => '',
                'type' => 'pilihan_ganda',
                'options' => [],
                'explanation' => '',
                'difficulty' => 'sedang',
                'points' => 1,
                'is_active' => true,
            ];
        }

        // Check for markers
        if (preg_match('/^\[SOAL\]/i', $text)) {
            $this->currentQuestion['content'] = $this->getElementHtml($element, '[SOAL]');
            $this->currentState = 'soal';
        } elseif (preg_match('/^\[([A-Z])\]/i', $text, $matches)) {
            $label = strtoupper($matches[1]);
            $marker = '[' . $matches[1] . ']';
            $this->currentQuestion['options'][$label] = [
                'content' => $this->getElementHtml($element, $marker),
                'is_correct' => false,
            ];
            $this->currentState = 'option_' . $label;
        } elseif (preg_match('/^\[KUNCI\]/i', $text)) {
            $kunciText = trim(preg_replace('/^\[KUNCI\]\s*/i', '', $text));
            if ($this->currentQuestion['type'] === 'isian_singkat') {
                $kuncis = array_filter(array_map('trim', explode(',', $kunciText)));
                foreach ($kuncis as $kunci) {
                    $this->currentQuestion['options'][$kunci] = [
                        'content' => $kunci,
                        'is_correct' => true,
                    ];
                }
            } else {
                $kuncis = array_filter(array_map('trim', explode(',', strtoupper($kunciText))));

                foreach ($kuncis as $kunci) {
                    if (isset($this->currentQuestion['options'][$kunci])) {
                        $this->currentQuestion['options'][$kunci]['is_correct'] = true;
                    }
                }
                // If multiple keys, change type to complex if it's currently pilihan_ganda
                if (count($kuncis) > 1 && $this->currentQuestion['type'] === 'pilihan_ganda') {
                    $this->currentQuestion['type'] = 'pilihan_ganda_kompleks';
                }
            }
            $this->currentState = 'kunci';
        } elseif (preg_match('/^\[PEMBAHASAN\]/i', $text)) {
            $this->currentQuestion['explanation'] = $this->getElementHtml($element, '[PEMBAHASAN]');
            $this->currentState = 'explanation';
        } elseif (preg_match('/^\[TIPE\]/i', $text)) {
            $typeInput = trim(strtolower(preg_replace('/^\[TIPE\]\s*/i', '', $text)));
            $allowedTypes = ['pilihan_ganda', 'pilihan_ganda_kompleks', 'essai', 'menjodohkan', 'benar_salah', 'isian_singkat'];
            if (in_array($typeInput, $allowedTypes)) {
                $this->currentQuestion['type'] = $typeInput;
            }
            $this->currentState = 'type';
        } elseif (preg_match('/^\[POIN\]/i', $text)) {
            $poinInput = trim(preg_replace('/^\[POIN\]\s*/i', '', $text));
            if (is_numeric($poinInput)) {
                $this->currentQuestion['points'] = (float) $poinInput;
            }
            $this->currentState = 'points';
        } elseif (preg_match('/^\[KESULITAN\]/i', $text)) {
            $diffInput = trim(strtolower(preg_replace('/^\[KESULITAN\]\s*/i', '', $text)));
            $allowedDiffs = ['mudah', 'sedang', 'sulit'];
            if (in_array($diffInput, $allowedDiffs)) {
                $this->currentQuestion['difficulty'] = $diffInput;
            }
            $this->currentState = 'difficulty';
        } elseif (preg_match('/^---/i', $text)) {
            // Already handled above
        } else {
            // Append to current state
            if ($this->currentQuestion) {
                if ($this->currentState === 'soal') {
                    if ($html !== '') {
                        $this->currentQuestion['content'] = ($this->currentQuestion['content'] !== '' ? $this->currentQuestion['content'] . '<br>' : '') . $html;
                    }
                } elseif (str_starts_with($this->currentState, 'option_')) {
                    $label = str_replace('option_', '', $this->currentState);
                    if (isset($this->currentQuestion['options'][$label]) && $html !== '') {
                        $this->currentQuestion['options'][$label]['content'] = ($this->currentQuestion['options'][$label]['content'] !== '' ? $this->currentQuestion['options'][$label]['content'] . '<br>' : '') . $html;
                    }
                } elseif ($this->currentState === 'explanation') {
                    if ($html !== '') {
                        $this->currentQuestion['explanation'] = ($this->currentQuestion['explanation'] !== '' ? $this->currentQuestion['explanation'] . '<br>' : '') . $html;
                    }
                }
            }
        }
    }

    protected function getElementText($element)
    {
        if ($element instanceof TextRun || $element instanceof ListItemRun) {
            $text = '';
            foreach ($element->getElements() as $child) {
                if ($child instanceof Text || $child instanceof Link || $child instanceof ListItem) {
                    $text .= htmlspecialchars_decode($child->getText(), ENT_QUOTES);
                } elseif ($child instanceof TextBreak) {
                    $text .= "\n";
                } elseif (method_exists($child, 'getText')) {
                    $text .= htmlspecialchars_decode($child->getText(), ENT_QUOTES);
                }
            }
            return $text;
        }

        if ($element instanceof Text || $element instanceof Link || $element instanceof ListItem) {
            return htmlspecialchars_decode($element->getText(), ENT_QUOTES);
        }

        if ($element instanceof TextBreak) {
            return "\n";
        }

        if (method_exists($element, 'getText')) {
            return htmlspecialchars_decode($element->getText(), ENT_QUOTES);
        }

        return '';
    }

    protected function getElementHtml($element, $stripMarker = null)
    {
        if ($element instanceof Table) {
            return $this->renderTableToHtml($element);
        }

        if ($element instanceof TextBreak) {
            return '<br>';
        }

        $html = '';
        if ($element instanceof TextRun || $element instanceof ListItemRun) {
            $markerToStrip = $stripMarker;
            foreach ($element->getElements() as $child) {
                if ($child instanceof Text) {
                    $formatted = $this->formatTextElement($child, $markerToStrip);
                    $html .= $formatted;
                    if ($markerToStrip !== null && !empty(trim(htmlspecialchars_decode($child->getText(), ENT_QUOTES)))) {
                        $markerToStrip = null;
                    }
                } elseif ($child instanceof Link) {
                    $formatted = $this->formatLinkElement($child, $markerToStrip);
                    $html .= $formatted;
                    if ($markerToStrip !== null && !empty(trim(htmlspecialchars_decode($child->getText(), ENT_QUOTES)))) {
                        $markerToStrip = null;
                    }
                } elseif ($child instanceof Image) {
                    $imagePath = $this->saveImage($child);
                    if ($imagePath) {
                        $html .= '<img src="' . Storage::url($imagePath) . '" class="max-w-full h-auto my-2">';
                    }
                } elseif ($child instanceof TextBreak) {
                    $html .= '<br>';
                }
            }
        } elseif ($element instanceof Text) {
            $html = $this->formatTextElement($element, $stripMarker);
        } elseif ($element instanceof Link) {
            $html = $this->formatLinkElement($element, $stripMarker);
        } elseif ($element instanceof Image) {
            $imagePath = $this->saveImage($element);
            if ($imagePath) {
                $html = '<img src="' . Storage::url($imagePath) . '" class="max-w-full h-auto my-2">';
            }
        }

        return $html;
    }

    protected function formatTextElement(Text $textElement, $stripMarker = null)
    {
        $rawText = $textElement->getText();
        $plainText = htmlspecialchars_decode($rawText, ENT_QUOTES);

        if ($stripMarker !== null) {
            $pattern = '/^\s*' . preg_quote($stripMarker, '/') . '\s*/i';
            $plainText = preg_replace($pattern, '', $plainText);
        }

        if ($plainText === '') {
            return '';
        }

        $text = htmlspecialchars($plainText, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $fontStyle = $textElement->getFontStyle();

        if ($fontStyle) {
            if ($fontStyle->isBold()) {
                $text = '<strong>' . $text . '</strong>';
            }
            if ($fontStyle->isItalic()) {
                $text = '<em>' . $text . '</em>';
            }
            if ($fontStyle->getUnderline() && $fontStyle->getUnderline() !== 'none') {
                $text = '<u>' . $text . '</u>';
            }
            if ($fontStyle->isStrikethrough()) {
                $text = '<s>' . $text . '</s>';
            }
            if ($fontStyle->isSuperScript()) {
                $text = '<sup>' . $text . '</sup>';
            } elseif ($fontStyle->isSubScript()) {
                $text = '<sub>' . $text . '</sub>';
            }
            if ($fontStyle->getColor()) {
                $color = $fontStyle->getColor();
                if (!str_starts_with($color, '#')) {
                    $color = '#' . $color;
                }
                $text = '<span style="color: ' . htmlspecialchars($color, ENT_QUOTES, 'UTF-8') . ';">' . $text . '</span>';
            }
        }

        return $text;
    }

    protected function formatLinkElement(Link $linkElement, $stripMarker = null)
    {
        $rawText = $linkElement->getText();
        $plainText = htmlspecialchars_decode($rawText, ENT_QUOTES);

        if ($stripMarker !== null) {
            $pattern = '/^\s*' . preg_quote($stripMarker, '/') . '\s*/i';
            $plainText = preg_replace($pattern, '', $plainText);
        }

        if ($plainText === '') {
            return '';
        }

        $text = htmlspecialchars($plainText, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $target = htmlspecialchars($linkElement->getTarget(), ENT_QUOTES, 'UTF-8');

        return '<a href="' . $target . '" target="_blank" class="text-indigo-600 underline">' . $text . '</a>';
    }

    protected function renderTableToHtml(Table $table)
    {
        $html = '<table class="w-full border-collapse border border-slate-300 my-4 text-sm">';
        foreach ($table->getRows() as $row) {
            $html .= '<tr>';
            foreach ($row->getCells() as $cell) {
                $html .= '<td class="border border-slate-300 p-2">';
                foreach ($cell->getElements() as $element) {
                    $html .= $this->getElementHtml($element);
                }
                $html .= '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</table>';
        return $html;
    }

    protected function saveImage($imageElement)
    {
        $base64Data = $imageElement->getImageStringData(true);
        if (!$base64Data)
            return null;

        $imageData = base64_decode($base64Data);
        $extension = $imageElement->getImageExtension();
        $filename = 'questions/' . Str::random(40) . '.' . $extension;

        Storage::disk('public')->put($filename, $imageData);
        return $filename;
    }

    protected function saveToDatabase()
    {
        $importedCount = 0;
        foreach ($this->questions as $qData) {
            // Safety check: ensure subject_id exists and question is not empty
            if (!isset($qData['subject_id'])) {
                continue;
            }
            $hasContent = !empty(trim(strip_tags($qData['content']))) || str_contains($qData['content'], '<img');
            if (!$hasContent && empty($qData['options'])) {
                continue;
            }

            $question = Question::create([
                'subject_id' => $qData['subject_id'],
                'type' => $qData['type'],
                'content' => $qData['content'] ?: '-',
                'explanation' => $qData['explanation'],
                'difficulty' => $qData['difficulty'],
                'points' => $qData['points'] ?? 1,
                'is_active' => $qData['is_active'],
                'created_by' => auth()->id(),
            ]);

            foreach ($qData['options'] as $label => $optData) {
                $content = $optData['content'] ?: '-';
                $dbLabel = $label;

                if ($qData['type'] === 'menjodohkan' && strpos($content, '=') !== false) {
                    $parts = explode('=', $content, 2);
                    $dbLabel = trim($parts[0]);
                    $content = trim($parts[1]);
                }

                QuestionOption::create([
                    'question_id' => $question->id,
                    'label' => $dbLabel,
                    'content' => $content,
                    'is_correct' => $optData['is_correct'],
                ]);
            }
            $importedCount++;
        }
        return $importedCount;
    }
}
