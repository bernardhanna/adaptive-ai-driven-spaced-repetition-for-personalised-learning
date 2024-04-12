<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert the OOP course
        $courseId = DB::table('courses')->insertGetId([
            'slug' => 'oop',
            'course_name' => 'OOP',
            'description' => 'OOP Course Description',
            'difficulty' => 'Beginner',
            'category' => 'Programming',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Define topics for OOP course
        $topics = [
            ['course_id' => $courseId, 'name' => 'Basic'],
            // Add more topics for OOP course here
        ];

        // Insert topics for OOP course
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
                    'question' => 'Type the correct number that corresponds to correct answer(s) in the list below.',
                    'image_url' => '/images/questions/q1.jpg',
                    'answer' => '3',
                    'difficulty' => 'Very Easy',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Type the correct number that corresponds to correct answer(s) in the list below.',
                    'image_url' => '/images/questions/q2.jpg',
                    'answer' => '3 4',
                    'difficulty' => 'Easy',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Type the correct number that corresponds to correct answer(s) in the list below.',
                    'image_url' => '/images/questions/q3.jpg',
                    'answer' => '2',
                    'difficulty' => 'Easy',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Type the correct number that corresponds to correct answer(s) in the list below.',
                    'image_url' => '/images/questions/q4.jpg',
                    'answer' => '1',
                    'difficulty' => 'Easy',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Type the correct number that corresponds to correct answer(s) in the list below.',
                    'image_url' => '/images/questions/q5.jpg',
                    'answer' => '1 3 5',
                    'difficulty' => 'Easy',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Type the correct number that corresponds to correct answer(s) in the list below.',
                    'image_url' => '/images/questions/q6.jpg',
                    'answer' => '3',
                    'difficulty' => 'Easy',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Type the correct number that corresponds to correct answer(s) in the list below.',
                    'image_url' => '/images/questions/q7.jpg',
                    'answer' => '2',
                    'difficulty' => 'Easy',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Type the correct number that corresponds to correct answer(s) in the list below.',
                    'image_url' => '/images/questions/q8.jpg',
                    'answer' => '2',
                    'difficulty' => 'Easy',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Type the correct number that corresponds to correct answer(s) in the list below.',
                    'image_url' => '/images/questions/q9.jpg',
                    'answer' => '3',
                    'difficulty' => 'Tough',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Type the correct number that corresponds to correct answer(s) in the list below.',
                    'image_url' => '/images/questions/q10.jpg',
                    'answer' => '2',
                    'difficulty' => 'Tough',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Type the correct number that corresponds to correct answer(s) in the list below.',
                    'image_url' => '/images/questions/q11.jpg',
                    'answer' => '1 2 3 4',
                    'difficulty' => 'Easy',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Type the correct number that corresponds to correct answer(s) in the list below.',
                    'image_url' => '/images/questions/q12.jpg',
                    'answer' => '3 4',
                    'difficulty' => 'Tough',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Type the correct number that corresponds to correct answer(s) in the list below.',
                    'image_url' => '/images/questions/q13.jpg',
                    'answer' => '4',
                    'difficulty' => 'Easy',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Type the correct number that corresponds to correct answer(s) in the list below.',
                    'image_url' => '/images/questions/q14.jpg',
                    'answer' => '3',
                    'difficulty' => 'Easy',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Type the correct number that corresponds to correct answer(s) in the list below.',
                    'image_url' => '/images/questions/q15.jpg',
                    'answer' => '5',
                    'difficulty' => 'Tough',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Type the correct number that corresponds to correct answer(s) in the list below.',
                    'image_url' => '/images/questions/q16.jpg',
                    'answer' => '2',
                    'difficulty' => 'Very Easy',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Type the correct number that corresponds to correct answer(s) in the list below.',
                    'image_url' => '/images/questions/q17.jpg',
                    'answer' => '1 2 3 4',
                    'difficulty' => 'Very Easy',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Type the correct number that corresponds to correct answer(s) in the list below.',
                    'image_url' => '/images/questions/q18.jpg',
                    'answer' => '1',
                    'difficulty' => 'Very Tough',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Type the correct number that corresponds to correct answer(s) in the list below.',
                    'image_url' => '/images/questions/q19.jpg',
                    'answer' => '4',
                    'difficulty' => 'Tough',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Type the correct number that corresponds to correct answer(s) in the list below.',
                    'image_url' => '/images/questions/q20.jpg',
                    'answer' => '3 5',
                    'difficulty' => 'Tough',
                    'type' => 'text',
                    'course_id' => $courseId,
                ],
            ];

            // Insert questions for the OOP course
            foreach ($questions as $question) {
                DB::table('questions')->insert([
                    'topic_id' => $question['topic_id'],
                    'question' => $question['question'],
                    'answer' => $question['answer'],
                    'image_url' => $question['image_url'],
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
