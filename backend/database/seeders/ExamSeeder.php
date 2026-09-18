<?php

namespace Database\Seeders;

use App\Models\Exam;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    /**
     * Creates the 20-minute practice aptitude/achievement exam.
     */
    public function run(): void
    {
        $exam = Exam::create([
            'title' => 'اختبار القدرات والتحصيلي التجريبي',
            'duration_minutes' => 20,
        ]);

        // The option at the correct index is stored as is_correct = true.
        $questions = [
            ['text' => 'ما هو ناتج العملية التالية؟ 7 + 8 = ؟', 'options' => ['10', '15', '20', '25'], 'correct' => 1],
            ['text' => 'أي من التالي يعتبر من أنظمة التشغيل؟', 'options' => ['Microsoft Word', 'Windows', 'Google Chrome', 'Photoshop'], 'correct' => 1],
            ['text' => 'أكمل المتتالية العددية التالية: 2، 4، 8، 16، ...؟', 'options' => ['18', '24', '32', '20'], 'correct' => 2],
            ['text' => 'ما مرادف كلمة "سخي"؟', 'options' => ['بخيل', 'كريم', 'غاضب', 'خجول'], 'correct' => 1],
            ['text' => 'ما ناتج العملية التالية؟ 15 × 3 = ؟', 'options' => ['35', '40', '45', '50'], 'correct' => 2],
            ['text' => 'ما عاصمة المملكة العربية السعودية؟', 'options' => ['جدة', 'الرياض', 'الدمام', 'مكة المكرمة'], 'correct' => 1],
            ['text' => 'أي الكلمات التالية لا تنتمي إلى مجموعة الفواكه؟', 'options' => ['تفاح', 'برتقال', 'جزر', 'عنب'], 'correct' => 2],
            ['text' => 'إذا كان س + 5 = 12، فما قيمة س؟', 'options' => ['5', '6', '7', '8'], 'correct' => 2],
            ['text' => 'أي مما يلي يعتبر لغة برمجة؟', 'options' => ['Python', 'Photoshop', 'Excel', 'Windows'], 'correct' => 0],
            ['text' => 'ما مضاد كلمة "سريع"؟', 'options' => ['بطيء', 'قوي', 'ضعيف', 'هادئ'], 'correct' => 0],
        ];

        foreach ($questions as $questionData) {
            $question = $exam->questions()->create([
                'question' => $questionData['text'],
            ]);

            $question->options()->createMany(
                collect($questionData['options'])
                    ->map(fn (string $option, int $index) => [
                        'option' => $option,
                        'is_correct' => $index === $questionData['correct'],
                    ])
                    ->all()
            );
        }
    }
}
