window.toggleQuiz = function(checkbox) {
  const quizSection = document.getElementById('quiz-section');
  if (quizSection) {
    quizSection.style.display = checkbox.checked ? 'block' : 'none';
    if (checkbox.checked && window.questionCount === 0) window.addQuestion();
  }
};

window.addQuestion = function() {
  const qi = window.questionCount++;
  const container = document.getElementById('questions-container');
  if (container) {
    container.insertAdjacentHTML('beforeend', window.questionHTML(qi));
  }
};

window.questionHTML = function(qi) {
  return `
    <div class="card-box mb-3" id="question-${qi}" style="background:var(--edu-surface)">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
        <div style="font-weight:600;font-size:13px">Question ${qi + 1}</div>
        <button type="button" onclick="removeQuestion(${qi})"
          style="border:none;background:none;color:var(--edu-red);cursor:pointer;font-size:12px">
          <i class="bi bi-trash"></i> Remove
        </button>
      </div>
      <div class="field-group">
        <div class="field-wrap">
          <i class="bi bi-question-circle field-icon"></i>
          <input type="text" name="questions[${qi}][question_text]"
            class="field-input" placeholder="Enter your question..." required>
        </div>
      </div>
      <div id="options-${qi}" style="display:flex;flex-direction:column;gap:8px;margin-bottom:10px">
        ${window.optionHTML(qi, 0)}
        ${window.optionHTML(qi, 1)}
      </div>
      <button type="button" onclick="addOption(${qi})"
        class="btn-outline-edu btn-sm py-1 px-3" style="font-size:12px">
        <i class="bi bi-plus"></i> Add Option
      </button>
    </div>
  `;
};

window.optionHTML = function(qi, oi) {
  return `
    <div style="display:flex;align-items:center;gap:8px" id="option-${qi}-${oi}">
      <input type="radio" name="questions[${qi}][correct_option]" value="${oi}" required>
      <input type="text" name="questions[${qi}][options][${oi}][text]"
        class="field-input" style="flex:1;padding-left:14px" placeholder="Option ${oi + 1}" required>
      <button type="button" onclick="removeOption('${qi}','${oi}')"
        style="border:none;background:none;color:var(--edu-red);cursor:pointer">
        <i class="bi bi-x-lg"></i>
      </button>
    </div>
  `;
};

window.addOption = function(qi) {
  const container = document.getElementById(`options-${qi}`);
  if (container) {
    container.insertAdjacentHTML('beforeend', window.optionHTML(qi, container.children.length));
  }
};

window.removeOption = function(qi, oi) { document.getElementById(`option-${qi}-${oi}`)?.remove(); };
window.removeQuestion = function(qi) { 
  document.getElementById(`question-${qi}`)?.remove();
  questionCount--;
};