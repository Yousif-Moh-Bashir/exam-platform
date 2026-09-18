<template>
  <main dir="rtl">
    <header class="topbar">
      <div class="brand">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3 1 8l11 5 9-4.1V17h2V8L12 3Z" fill="#d4af37" /><path d="M5 10.5V15c0 2 3 4 7 4s7-2 7-4v-4.5l-7 3.2-7-3.2Z" fill="#fff" opacity=".9" /></svg>
        <span>منصة الاختبار</span>
      </div>
      <div class="status"><div class="student"><span>{{ studentName }}</span></div></div>
    </header>
    <section class="results-shell">
      <p v-if="loading" class="page-message">جارٍ تحميل النتيجة...</p>
      <p v-else-if="error" class="page-message error-message">{{ error }}</p>
      <article v-else-if="result" class="result-card">
        <div class="result-icon"><svg width="30" height="30" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m5 13 4 4L19 7" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" /></svg></div>
        <h1>نتيجة الاختبار</h1>
        <div class="score-ring">
          <svg width="190" height="190" viewBox="0 0 190 190" aria-label="النسبة المئوية"><circle cx="95" cy="95" r="82" fill="none" stroke="#e1e7f0" stroke-width="14" /><circle class="ring-progress" cx="95" cy="95" r="82" fill="none" stroke="#1fa971" stroke-width="14" stroke-linecap="round" :style="ringStyle" /></svg>
          <div class="ring-label"><span class="pct">{{ result.percentage }}%</span><span class="pct-sub">النسبة المئوية</span></div>
        </div>
        <div class="stat-row">
          <div class="stat unanswered"><div class="icon">◯</div><div class="num">{{ result.unanswered }}</div><div class="label">غير المُجاب</div></div>
          <div class="stat wrong"><div class="icon">×</div><div class="num">{{ result.wrong }}</div><div class="label">الخطأ</div></div>
          <div class="stat correct"><div class="icon">✓</div><div class="num">{{ result.correct }}</div><div class="label">الصحيح</div></div>
        </div>
        <button type="button" class="btn btn-navy btn-block" @click="goHome">⌂&nbsp; العودة للرئيسية</button>
      </article>
    </section>
  </main>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../services/api'
import type { ApiResponse, Result, Student } from '../types'
const route = useRoute(); const router = useRouter(); const result = ref<Result | null>(null); const loading = ref(true); const error = ref(''); const circumference = 2 * Math.PI * 82
const ringStyle = computed(() => ({ strokeDasharray: `${circumference}`, strokeDashoffset: `${circumference * (1 - (result.value?.percentage ?? 0) / 100)}` }))
const studentName = computed(() => { try { return (JSON.parse(localStorage.getItem('exam_student') ?? 'null') as Student | null)?.name ?? '—' } catch { return '—' } })
onMounted(async () => { try { result.value = (await api.get<ApiResponse<Result>>(`/attempts/${route.params.id}/result`)).data.data } catch (err: any) { error.value = err.response?.data?.message ?? 'تعذر تحميل النتيجة.' } finally { loading.value = false } })
function goHome() { localStorage.removeItem('exam_student'); void router.replace('/login') }
</script>
