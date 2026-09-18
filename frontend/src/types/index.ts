// ============ الكيانات الأساسية ============

export interface Student {
  id: number
  name: string
  created_at: string
}

export interface Option {
  id: number
  text: string
}

export interface Question {
  id: number
  question: string
  options: Option[]
}

export interface Exam {
  id: number
  title: string
  duration_minutes: number
  questions_count?: number
  questions?: Question[]
}

export interface Answer {
  id: number
  question_id: number
  option_id: number | null
  is_flagged: boolean
  question?: Question
  option?: Option
}

export interface Attempt {
  id: number
  student_id: number
  exam_id: number
  status: 'in_progress' | 'completed'
  started_at: string
  submitted_at: string | null
  score?: number
  answers?: Answer[]
  exam?: Exam
}

export interface Result {
  attempt_id: number
  score: number
  percentage: number
  total: number
  correct: number
  wrong: number
  unanswered: number
}

// ============ استجابات API ============

export interface ApiResponse<T> {
  message?: string
  data: T
}