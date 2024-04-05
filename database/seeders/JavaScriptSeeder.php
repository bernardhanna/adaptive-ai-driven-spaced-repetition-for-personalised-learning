<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JavaScriptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert the JavaScript course
        $courseId = DB::table('courses')->insertGetId([
            'slug' => 'javascript',
            'course_name' => 'JavaScript',
            'description' => 'JavaScript Course Description',
            'difficulty' => 'Beginner',
            'category' => 'Programming',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Define topics for JavaScript course
        $topics = [
            ['course_id' => $courseId, 'name' => 'Basic'],
            // Add more topics for JavaScript course here
        ];

        // Insert topics for JavaScript course
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
                    'question' => 'Fill in the blank: alert_"You\'re learning JavaScript!"_;',
                    'answer' => '()',
                    'difficulty' => 'Easy',
                    'type' => 'fill-in-the-blank',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Type a string with the text Hello World',
                    'answer' => '"Hello World"',
                    'difficulty' => 'Easy',
                    'type' => 'text',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Type an alert that displays the text "Hi"',
                    'answer' => 'alert("Hi");',
                    'difficulty' => 'Easy',
                    'type' => 'text',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What is the keyword that you use to declare a variable?',
                    'answer' => 'let',
                    'difficulty' => 'Easy',
                    'type' => 'text',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'In a single statement, declare the variable "name" and assign the string "Al" to it',
                    'answer' => 'let name = "Al";',
                    'difficulty' => 'Easy',
                    'type' => 'text',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Declare a variable "username" without assigning it a value',
                    'answer' => 'let username;',
                    'difficulty' => 'Easy',
                    'type' => 'text',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'The following statement has already been coded. - let friend = "Charlie"; - Assign the variable a new string "Bill"',
                    'answer' => 'friend = "Bill";',
                    'difficulty' => 'Easy',
                    'type' => 'text',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Declare a variable call "x" without assigning it a value. Then, in a second statement, assign it the value 5.',
                    'answer' => 'let x;
                    x = 5;',
                    'difficulty' => 'Easy',
                    'type' => 'textarea',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'In a single statement, declare a variable called "name" without assigning it a value. Then assign it a string called "Jill". Then Code an alert, specifying the variable, not the string, as the message.',
                    'answer' => 'let name;
                    name = "Jill";
                    alert(name);',
                    'difficulty' => 'Easy',
                    'type' => 'textarea',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'If a number is enclosed in quotes, it\'s a_______? ',
                    'answer' => 'string',
                    'difficulty' => 'Easy',
                    'type' => 'fill-in-the-blank',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => "let merchTotal = 100;\nlet shippingCharge = 10;\nlet orderTotal = merchTotal + shippingCharge;\nWhat is the value of orderTotal?",
                    'answer' => '110',
                    'difficulty' => 'Easy',
                    'type' => 'text',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'In a single statement declare a variable called "total" and assign to it the sum of 2 other variables called "price" and "tax',
                    'answer' => 'let total = price + tax;',
                    'difficulty' => 'Easy',
                    'type' => 'text',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Assign the sum of 2 numbers to a variable, which has not been declared beforehand. The variable name is total and the numbers are 2 and 3',
                    'answer' => 'let total = 2 + 3;',
                    'difficulty' => 'Easy',
                    'type' => 'text',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'In one statement declare a variable called x. In a second statement assign it the sum of 2 numbers. use the number 2 for both numbers.',
                    'answer' => '
                    let x;
                    x = 2 + 2;',
                    'difficulty' => 'Easy',
                    'type' => 'textarea',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Enter the operators, in this order—for addition, subtraction, multiplication, and division. Dont type spaces between them.',
                    'answer' => '+-*/',
                    'difficulty' => 'Easy',
                    'type' => 'text',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'What is the name of the arithmetic operator that gives you the remainder when one number is divided by another? (one word)',
                    'answer' => 'modulus',
                    'difficulty' => 'Easy',
                    'type' => 'text',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Type the modulus operator.',
                    'answer' => '%%',
                    'difficulty' => 'Easy',
                    'type' => 'text',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Fill in the blank with the expression 100 multiplied by 200.
                    let num = ____________;',
                    'answer' => '100 * 200',
                    'difficulty' => 'Easy',
                    'type' => 'fill-in-the-blank',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Fill in the blank. The expression is 9 divided by the variable qty.
                    let num = ____________;',
                    'answer' => '9 / qty',
                    'difficulty' => 'Easy',
                    'type' => 'fill-in-the-blank',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'course_id' => $courseId,
                ],
                [
                    'topic_id' => $topicId,
                    'question' => 'Code the short way of writing x = x + 1; Use either of the two legal expressions.',
                    'answer' => json_encode(['x++', '++x']), // Store answers as a JSON string
                    'difficulty' => 'Easy',
                    'type' => 'text',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'course_id' => $courseId,
                ],
            ];

            // Insert questions for the JavaScript course
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
