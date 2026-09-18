Exam Simulation Platform

A full-stack exam simulation platform built with Laravel 12 and Vue.js.

This project was developed as a technical assessment for a Full-Stack Developer position. It demonstrates a complete exam flow from student entry and exam attempt creation to answering questions, flagging questions, submission, and displaying the final result.

Features

Student login using name

Start an exam attempt

10 multiple-choice questions

Countdown timer

Automatic submission when the timer expires

Navigate between questions

Question status:

Unanswered

Answered

Current

Flagged for review

Flag/unflag questions for review

Automatically save selected answers through the API

Submit the exam manually

Prevent submitting a completed attempt again

Calculate score and percentage

Display correct, wrong, and unanswered questions

Laravel REST API

Vue.js frontend

Axios API integration

Basic request validation and error handling

Technology Stack

Backend

PHP

Laravel 12

MySQL

Eloquent ORM

REST API

Frontend

Vue.js 3

TypeScript

Vue Router

Axios

Vite

HTML5

CSS3

Project Structure

exam-platform/
│
├── README.md
├── AI_WORKFLOW.md
│
├── backend/
│   ├── app/
│   │   ├── Http/
│   │   │   └── Controllers/
│   │   │       └── Api/
│   │   │           └── AttemptController.php
│   │   │
│   │   ├── Models/
│   │   │   ├── Student.php
│   │   │   ├── Exam.php
│   │   │   ├── Question.php
│   │   │   ├── Option.php
│   │   │   ├── Attempt.php
│   │   │   └── Answer.php
│   │   │
│   │   └── Services/
│   │       └── ExamService.php
│   │
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   │
│   ├── routes/
│   │   └── api.php
│   │
│   ├── composer.json
│   └── artisan
│
└── frontend/
    ├── src/
    │   ├── views/
    │   │   ├── Login.vue
    │   │   ├── Exam.vue
    │   │   └── Result.vue
    │   │
    │   ├── router/
    │   │   └── index.js
    │   │
    │   ├── services/
    │   │   └── api.ts
    │   │
    │   ├── assets/
    │   │   └── main.css
    │   │
    │   ├── App.vue
    │   └── main.ts
    │
    ├── package.json
    └── vite.config.ts

The structure above describes the intended organization of the project. Keep the README aligned with the actual repository structure if files are moved or renamed.

Database Relationships

The main entities are:

Student
   │
   └── hasMany → Attempt
                    │
                    ├── belongsTo → Exam
                    │
                    └── hasMany → Answer
                                      │
                                      ├── belongsTo → Question
                                      └── belongsTo → Option

Additional relationships:

Exam
 └── hasMany → Question

Question
 └── hasMany → Option

Exam Attempt Lifecycle

An attempt starts with:

in_progress

After submission:

completed

A completed attempt cannot be submitted again.

API Endpoints

The API base URL is:

http://127.0.0.1:8000/api

Start Exam Attempt

POST /api/attempts

Request:

{
    "student_id": 1,
    "exam_id": 1
}

Get Attempt

GET /api/attempts/{attempt}

Example:

GET /api/attempts/1

Returns the attempt information and saved answers.

Save Answer

POST /api/attempts/{attempt}/answer

Request:

{
    "question_id": 1,
    "option_id": 3
}

The backend verifies that the selected option belongs to the specified question.

Flag Question

POST /api/attempts/{attempt}/flag

Request:

{
    "question_id": 1,
    "flagged": true
}

To remove the flag:

{
    "question_id": 1,
    "flagged": false
}

Submit Exam

POST /api/attempts/{attempt}/submit

When submitted, the backend:

Calculates the score

Sets the attempt status to completed

Records submitted_at

Prevents the same completed attempt from being submitted again

Get Result

GET /api/attempts/{attempt}/result

Example response:

{
    "data": {
        "attempt_id": 1,
        "score": 8,
        "percentage": 80,
        "total": 10,
        "correct": 8,
        "wrong": 1,
        "unanswered": 1
    }
}

Requirements

Before running the project, install:

PHP 8.2 or higher

Composer

MySQL

Node.js

npm

Backend Installation

Navigate to the Laravel project:

cd backend

Install PHP dependencies:

composer install

Create the environment file.

Linux/macOS:

cp .env.example .env

Windows:

Copy .env.example and rename the copy to .env

Generate the Laravel application key:

php artisan key:generate

Database Configuration

Create a MySQL database, for example:

exam_platform

Configure the database values in .env:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=exam_platform
DB_USERNAME=root
DB_PASSWORD=

Use the database username and password configured on your local machine.

Run Migrations

Run:

php artisan migrate

If the repository contains seeders:

php artisan migrate --seed

Start Laravel

Run:

php artisan serve

Laravel will normally be available at:

http://127.0.0.1:8000

The API is available at:

http://127.0.0.1:8000/api

Frontend Installation

Open another terminal and navigate to:

cd frontend

Install JavaScript dependencies:

npm install

Start the Vite development server:

npm run dev

The frontend will normally be available at:

http://localhost:5173

Open the application:

http://localhost:5173/login

Axios Configuration

The Axios instance is located at:

frontend/src/services/api.ts

Example configuration:

import axios from 'axios'

const api = axios.create({
    baseURL: 'http://127.0.0.1:8000/api',
    headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
    },
})

export default api

Application Flow

Login
  │
  ▼
Start Exam
  │
  ▼
Create Attempt
  │
  ▼
Load Exam Questions
  │
  ▼
Answer Questions
  │
  ├── Save Answer
  │
  └── Flag Question
  │
  ▼
Submit Exam
  │
  ▼
Calculate Result
  │
  ▼
Result Page

Timer

The exam includes a countdown timer.

The timer:

Starts when the exam begins

Displays the remaining time

Counts down every second

Automatically submits the exam when the time reaches zero

Answer Saving

When a student selects an option, the frontend sends the selected answer to the Laravel API.

Example:

POST /api/attempts/1/answer

{
    "question_id": 5,
    "option_id": 18
}

The backend validates and stores the answer for the current attempt.

Question Flagging

Students can mark questions for later review.

Example:

POST /api/attempts/1/flag

{
    "question_id": 5,
    "flagged": true
}

The frontend reflects the flagged state in the question navigation panel.

Score Calculation

After submission, the backend calculates the score by checking the selected option against the correct option.

The result contains:

Total questions

Correct answers

Wrong answers

Unanswered questions

Score

Percentage

Validation and Error Handling

The backend validates:

Student ID

Exam ID

Question ID

Option ID

Selected option ownership

Attempt state

For example, an already completed attempt cannot be submitted again.

API Testing

The API can be tested using:

Postman

Insomnia

The Vue.js frontend

Example request:

POST http://127.0.0.1:8000/api/attempts

{
    "student_id": 1,
    "exam_id": 1
}

Demo

The demo demonstrates the complete exam workflow:

Student login

Starting the exam

Displaying questions

Selecting answers

Navigating between questions

Flagging questions for review

Countdown timer

Submitting the exam

Calculating the result

Displaying the result

A short demo video can be added to the repository or shared with the assessment submission.

AI Workflow

AI tools were used as development assistants during the implementation.

The AI workflow, example prompts, and manual review process are documented in:

AI_WORKFLOW.md

AI suggestions were reviewed, tested, and adapted manually before being integrated into the project.

Development Focus

This project demonstrates:

Laravel API development

Vue.js frontend development

REST API integration

Eloquent relationships

Service-layer usage

Request validation

Exam attempt management

Answer persistence

Question flagging

Timer handling

Result calculation

Error handling

Git-based development workflow

Author

Yousif Mohamed Albashir

Full Stack Developer

Technical focus:

PHP

Laravel

Vue.js

JavaScript

TypeScript

REST APIs

MySQL

ERP Systems

Portfolio:

https://yousif-moh-dev.netlify.app

License

This project was created for technical assessment and demonstration purposes.

## Demo Video

[Watch the Demo Video](https://youtu.be/PtoMkR7l42w)
