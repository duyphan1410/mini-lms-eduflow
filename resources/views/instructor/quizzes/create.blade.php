@extends('layouts.app')
@section('title', 'Create Quiz')
@section('sidebar') @include('partials.sidebar_instructor') @endsection

@section('content')
<div class="topbar">
  <div class="page-header mb-0">
    <h2>Create Quiz</h2>
    <p>{{ $lesson->title }}</p>
  </div>
  <a href="{{ route('instructor.courses.lessons.quizzes.index', [$course, $lesson]) }}" class="btn-outline-edu">
    <i class="bi bi-arrow-left"></i> Back
  </a>
</div>

<form method="POST" action="{{ route('instructor.courses.lessons.quizzes.store', [$course, $lesson]) }}"
  id="quiz-form">
  @csrf

  <div class="card-box mb-3">
    <div class="field-group mb-0">
      <label class="field-label">Quiz Title</label>
      <div class="field-wrap">
        <i class="bi bi-patch-question field-icon"></i>
        <input type="text" name="title" value="{{ old('title') }}"
          class="field-input {{ $errors->has('title') ? 'is-invalid' : '' }}"
          placeholder="e.g. HTML Basics Quiz" required autofocus>
      </div>
      @error('title') <div class="field-error">{{ $message }}</div> @enderror
    </div>
  </div>

  {{-- Questions container --}}
  <div id="questions-container">
    {{-- JS sẽ inject questions vào đây --}}
  </div>

  <div class="d-flex gap-2 mt-3">
    <button type="button" class="btn-outline-edu" onclick="addQuestion()">
      <i class="bi bi-plus-lg"></i> Add Question
    </button>
    <button type="submit" class="btn-primary-edu">
      <i class="bi bi-check-lg"></i> Save Quiz
    </button>
  </div>
</form>

<script>
let questionCount = 0;

function addQuestion() {
  const qi = questionCount++;
  const container = document.getElementById('questions-container');

  const html = `
    <div class="card-box mb-3" id="question-${qi}">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
        <div style="font-weight:600;font-size:14px">Question ${qi + 1}</div>
        <button type="button" onclick="removeQuestion(${qi})"
          style="border:none;background:none;color:var(--edu-red);cursor:pointer;font-size:13px">
          <i class="bi bi-trash"></i> Remove
        </button>
      </div>

      <div class="field-group">
        <label class="field-label">Question Text</label>
        <div class="field-wrap">
          <i class="bi bi-question-circle field-icon"></i>
          <input type="text" name="questions[${qi}][question_text]"
            class="field-input" placeholder="Nhập câu hỏi..." required>
        </div>
      </div>

      <label class="field-label">Options <span style="color:var(--edu-muted);font-weight:400">(chọn đáp án đúng)</span></label>
      <div id="options-${qi}" style="display:flex;flex-direction:column;gap:8px;margin-bottom:10px">
        ${optionHTML(qi, 0)}
        ${optionHTML(qi, 1)}
      </div>

      <button type="button" onclick="addOption(${qi})"
        class="btn-outline-edu btn-sm py-1 px-3" style="font-size:12px">
        <i class="bi bi-plus"></i> Add Option
      </button>
    </div>
  `;
  container.insertAdjacentHTML('beforeend', html);
}

function optionHTML(qi, oi) {
  return `
    <div style="display:flex;align-items:center;gap:8px" id="option-${qi}-${oi}">
      <input type="radio" name="questions[${qi}][correct_option]" value="${oi}" required
        title="Mark as correct">
      <input type="text" name="questions[${qi}][options][${oi}][text]"
        class="field-input" style="flex:1;padding-left:14px"
        placeholder="Option ${oi + 1}" required>
      <button type="button" onclick="removeOption('${qi}','${oi}')"
        style="border:none;background:none;color:var(--edu-red);cursor:pointer">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>
  `;
}

function addOption(qi) {
  const container = document.getElementById(`options-${qi}`);
  const oi = container.children.length;
  container.insertAdjacentHTML('beforeend', optionHTML(qi, oi));
}

function removeOption(qi, oi) {
  const el = document.getElementById(`option-${qi}-${oi}`);
  if (el) el.remove();
}

function removeQuestion(qi) {
  const el = document.getElementById(`question-${qi}`);
  if (el) el.remove();
}

// Thêm 1 câu hỏi mặc định khi load
addQuestion();
</script>
@endsection