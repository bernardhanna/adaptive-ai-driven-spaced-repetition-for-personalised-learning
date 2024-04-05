<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PythonSeeder extends Seeder
{
    public function run()
    {
        // Insert the Python course
        $courseId = DB::table('courses')->insertGetId([
            'slug' => 'general',
            'course_name' => 'General',
            'description' => 'General Knowledge',
            'difficulty' => 'Beginner',
            'category' => 'Random',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Define topics for Python course
        $topics = [
            ['course_id' => $courseId, 'name' => 'Basic'],
            // Add more topics for Python course here
        ];

        // Insert topics for Python course
        foreach ($topics as $topic) {
            $topicId = DB::table('topics')->insertGetId([
                'course_id' => $topic['course_id'],
                'name' => $topic['name'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Define questions for each topic
            $questions = [
                [
                    'topic_id' => $topicId,
                    'question' => 'What is 2 plus 2?',
                    'answer' => '4',
                    'difficulty' => 'Easy',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What is the capital of France?',
                    'answer' => 'Paris',
                    'difficulty' => 'Easy',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What color is the sky on a clear day?',
                    'answer' => 'Blue',
                    'difficulty' => 'Very Easy',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'How many legs do spiders have?',
                    'answer' => '8',
                    'difficulty' => 'Very Easy',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What is the opposite of cold?',
                    'answer' => 'Hot',
                    'difficulty' => 'Very Easy',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'How many days are there in a week?',
                    'answer' => '7',
                    'difficulty' => 'Very Easy',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What do bees produce?',
                    'answer' => 'Honey',
                    'difficulty' => 'Very Easy',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What do we breathe to live?',
                    'answer' => 'Air',
                    'difficulty' => 'Very Easy',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'How many colors are in a rainbow?',
                    'answer' => '7',
                    'difficulty' => 'Very Easy',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What gas do plants absorb from the atmosphere?',
                    'answer' => 'Carbon',
                    'difficulty' => 'Easy',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What is the hardest natural substance on Earth?',
                    'answer' => 'Diamond',
                    'difficulty' => 'Medium',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What is the largest mammal in the world?',
                    'answer' => 'Blue Whale',
                    'difficulty' => 'Medium',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What is the largest planet in our solar system?',
                    'answer' => 'Jupiter',
                    'difficulty' => 'Medium',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What is the smallest planet in our solar system?',
                    'answer' => 'Mercury',
                    'difficulty' => 'Medium',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What is the largest ocean on Earth?',
                    'answer' => 'Pacific Ocean',
                    'difficulty' => 'Medium',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What is the largest desert in the world?',
                    'answer' => 'Antarctica',
                    'difficulty' => 'Medium',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What is the largest country in the world?',
                    'answer' => 'Russia',
                    'difficulty' => 'Medium',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What is the smallest country in the world?',
                    'answer' => 'Vatican City',
                    'difficulty' => 'Medium',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Who is known as the father of computers?',
                    'answer' => 'Babbage',
                    'difficulty' => 'Easy',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What metal is the primary component of steel?',
                    'answer' => 'Iron',
                    'difficulty' => 'Medium',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What organ produces insulin in the human body?',
                    'answer' => 'Pancreas',
                    'difficulty' => 'Medium',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Which element has the highest electrical conductivity?',
                    'answer' => 'Silver',
                    'difficulty' => 'Medium',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What is the study of fungi called?',
                    'answer' => 'Mycology',
                    'difficulty' => 'Medium',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What geometric shape is generally used for stop signs?',
                    'answer' => 'Octagon',
                    'difficulty' => 'Medium',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Who wrote the play "Romeo and Juliet"?',
                    'answer' => 'Shakespeare',
                    'difficulty' => 'Medium',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What is the term for a word that is similar in meaning to another word?',
                    'answer' => 'Synonym',
                    'difficulty' => 'Medium',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What phenomenon explains the bending of light around obstacles?',
                    'answer' => 'Diffraction',
                    'difficulty' => 'Hard',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Which mathematician introduced the concept of the Cartesian coordinate system?',
                    'answer' => 'Descartes',
                    'difficulty' => 'Hard',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What is the largest moon of Saturn called?',
                    'answer' => 'Titan',
                    'difficulty' => 'Hard',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'In which country is the ancient city of Petra located?',
                    'answer' => 'Jordan',
                    'difficulty' => 'Hard',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What term describes the fear of being in a place or situation where escape is difficult?',
                    'answer' => 'Agoraphobia',
                    'difficulty' => 'Hard',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Who was the Greek god of time?',
                    'answer' => 'Chronos',
                    'difficulty' => 'Hard',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What is the chemical formula for ozone?',
                    'answer' => 'O3',
                    'difficulty' => 'Hard',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'In literature, who authored "The Metamorphosis"?',
                    'answer' => 'Kafka',
                    'difficulty' => 'Hard',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What principle is Albert Einstein best known for?',
                    'answer' => 'Relativity',
                    'difficulty' => 'Hard',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What is the term for the speed at which the universe is expanding?',
                    'answer' => 'Hubble',
                    'difficulty' => 'Extremely Hard',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Who formulated the laws of planetary motion?',
                    'answer' => 'Kepler',
                    'difficulty' => 'Extremely Hard',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What mathematical concept expresses the underlying symmetry in nature?',
                    'answer' => 'Fractals',
                    'difficulty' => 'Extremely Hard',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'In quantum mechanics, what principle dictates that certain pairs of physical properties cannot be simultaneously known to arbitrary precision?',
                    'answer' => 'Uncertainty',
                    'difficulty' => 'Extremely Hard',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What hypothetical particles are postulated to mediate the force of gravity in quantum field theory?',
                    'answer' => 'Gravitons',
                    'difficulty' => 'Almost Impossible',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'In the context of string theory, what term describes the theoretical one-dimensional strings that vibrate at different frequencies?',
                    'answer' => 'Strings',
                    'difficulty' => 'Almost Impossible',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What mathematical problem involves finding the distribution of prime numbers and is considered unsolved?',
                    'answer' => 'Riemann',
                    'difficulty' => 'Almost Impossible',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
            ];

            // Insert questions for the Python course
            foreach ($questions as $question) {
                DB::table('questions')->insert([
                    'topic_id' => $question['topic_id'],
                    'question' => $question['question'],
                    'answer' => $question['answer'],
                    'difficulty' => $question['difficulty'],
                    'type' => $question['type'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'course_id' => $question['course_id'],
                ]);
            }
        }
    }
}
