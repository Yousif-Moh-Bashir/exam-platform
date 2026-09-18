<template>
  <main class="login-shell" dir="rtl">
    <section class="login-card">
      <div class="cap"><svg width="30" height="30" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3 1 8l11 5 9-4.1V17h2V8L12 3Z" fill="#fff" /><path d="M5 10.5V15c0 2 3 4 7 4s7-2 7-4v-4.5l-7 3.2-7-3.2Z" fill="#fff" opacity=".9" /></svg></div>
      <h1>منصة الاختبار</h1>
      <p class="subtitle">اختبار محاكاة القدرات والتحصيلي</p>
      <p v-if="error" class="already-submitted show">{{ error }}</p>
      <form @submit.prevent="startExam" novalidate>
        <div class="field">
          <label for="student-name">أدخل اسمك للبدء في الاختبار</label>
          <div class="input-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2" /><path d="M4 20c0-4 3.6-6 8-6s8 2 8 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" /></svg><input id="student-name" v-model="studentName" type="text" placeholder="اسم الطالب" autocomplete="name" :disabled="submitting" /></div>
          <p v-if="showValidation" class="error-text show">يرجى إدخال اسمك قبل البدء.</p>
        </div>
        <button type="submit" class="btn btn-navy btn-block" :disabled="submitting">{{ submitting ? 'جارٍ التحضير...' : 'بدء الاختبار' }}</button>
      </form>
      <p class="login-footer">بالتوفيق لك في اختبارك 💙</p>
    </section>
  </main>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api'
import type { ApiResponse, Student } from '../types'
const router = useRouter(); const studentName = ref(''); const showValidation = ref(false); const error = ref(''); const submitting = ref(false)
async function startExam() {
  const name = studentName.value.trim(); showValidation.value = !name; error.value = ''; if (!name) return
  try { submitting.value = true; const response = await api.post<ApiResponse<Student>>('/students', { name }); localStorage.setItem('exam_student', JSON.stringify(response.data.data)); await router.push('/exam') }
  catch (err: any) { error.value = err.response?.data?.message ?? 'تعذر إنشاء حساب الطالب. حاول مرة أخرى.' }
  finally { submitting.value = false }
}
</script>
