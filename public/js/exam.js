// 試験用JavaScript
class ExamManager {
    constructor() {
        this.timeLimit = window.examData.timeLimit * 60; // 分を秒に変換
        this.timeRemaining = this.timeLimit;
        this.timer = null;
        this.isTimeUp = false;
        this.isSubmitting = false;
        
        this.init();
    }

    init() {
        this.startTimer();
        this.bindEvents();
        this.updateAnswerCount();
    }

    startTimer() {
        this.timer = setInterval(() => {
            this.timeRemaining--;
            this.updateTimerDisplay();

            if (this.timeRemaining <= 0) {
                this.timeUp();
            }
        }, 1000);
    }

    updateTimerDisplay() {
        const minutes = Math.floor(this.timeRemaining / 60);
        const seconds = this.timeRemaining % 60;
        
        document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
        document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');

        // 残り時間が少なくなったら警告色に
        const timerElement = document.getElementById('timer');
        if (this.timeRemaining <= 300) { // 5分以下
            timerElement.classList.add('timer-warning');
        }
        if (this.timeRemaining <= 60) { // 1分以下
            timerElement.classList.add('timer-danger');
        }
    }

    timeUp() {
        clearInterval(this.timer);
        this.isTimeUp = true;
        this.isSubmitting = true;
        
        // beforeunloadイベントリスナーを削除
        window.removeEventListener('beforeunload', this.beforeUnloadHandler);
        
        // 自動提出
        alert('制限時間が終了しました。試験を自動的に終了します。');
        this.submitExam();
    }

    bindEvents() {
        // 試験終了ボタン
        const finishBtn = document.getElementById('finishExamBtn');
        if (finishBtn) {
            finishBtn.addEventListener('click', () => {
                this.showFinishConfirmModal();
            });
        }

        // モーダルの確認ボタン
        const confirmBtn = document.getElementById('confirmFinish');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();
                console.log('Confirm button clicked');
                this.submitExam();
            });
        }

        // モーダルのキャンセルボタンと背景クリック
        const modal = document.getElementById('finishConfirmModal');
        if (modal) {
            // キャンセルボタン
            const cancelBtn = modal.querySelector('[data-dismiss="modal"]');
            if (cancelBtn) {
                cancelBtn.addEventListener('click', () => {
                    this.hideModal();
                });
            }

            // 背景クリックで閉じる
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    this.hideModal();
                }
            });
        }

        // 回答選択時のイベント
        const radioButtons = document.querySelectorAll('input[type="radio"]');
        radioButtons.forEach(radio => {
            radio.addEventListener('change', () => {
                this.updateAnswerCount();
            });
        });

        // ページ離脱時の警告
        this.beforeUnloadHandler = (e) => {
            if (!this.isTimeUp && !this.isSubmitting) {
                e.preventDefault();
                e.returnValue = '試験が実行中です。ページを離れると試験が終了されます。';
                return e.returnValue;
            }
        };
        
        window.addEventListener('beforeunload', this.beforeUnloadHandler);
    }

    updateAnswerCount() {
        const totalQuestions = window.examData.totalQuestions;
        const answeredCount = document.querySelectorAll('input[type="radio"]:checked').length;
        
        // 回答済み数を更新
        const answeredCountElement = document.getElementById('answeredCount');
        if (answeredCountElement) {
            answeredCountElement.textContent = answeredCount;
        }

        return { answered: answeredCount, total: totalQuestions };
    }

    showFinishConfirmModal() {
        const { answered, total } = this.updateAnswerCount();
        const modalMessage = document.getElementById('modalMessage');
        
        if (answered < total) {
            modalMessage.textContent = 'すべての問いに答えていません。終了してよろしいでしょうか？';
        } else {
            modalMessage.textContent = '試験を終了してよろしいですか？';
        }

        // バニラJavaScriptでモーダルを表示
        const modal = document.getElementById('finishConfirmModal');
        modal.style.display = 'flex';
        modal.classList.add('show');
        document.body.classList.add('modal-open');
        
        // 背景の作成
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        backdrop.id = 'modal-backdrop';
        document.body.appendChild(backdrop);
    }

    hideModal() {
        const modal = document.getElementById('finishConfirmModal');
        const backdrop = document.getElementById('modal-backdrop');
        
        if (modal) {
            modal.style.display = 'none';
            modal.classList.remove('show');
        }
        
        if (backdrop) {
            backdrop.remove();
        }
        
        document.body.classList.remove('modal-open');
    }

    submitExam() {
        console.log('submitExam() method called');
        this.isSubmitting = true;
        console.log('isSubmitting set to true');
        
        clearInterval(this.timer);
        console.log('Timer cleared');
        
        // beforeunloadイベントリスナーを削除
        window.removeEventListener('beforeunload', this.beforeUnloadHandler);
        console.log('beforeunload listener removed');
        
        // モーダルを閉じる
        this.hideModal();
        console.log('Modal hidden');
        
        // フォームを送信
        const form = document.getElementById('examForm');
        if (form) {
            console.log('Form found, submitting...', form);
            console.log('Form action:', form.action);
            console.log('Form method:', form.method);
            form.submit();
            console.log('Form submitted');
        } else {
            console.error('Form not found!');
        }
    }
}

// ページ読み込み時に初期化
document.addEventListener('DOMContentLoaded', function() {
    if (window.examData) {
        new ExamManager();
    }
});

// スムーズスクロール機能
function scrollToQuestion(questionNumber) {
    const questionElement = document.querySelector(`[data-question="${questionNumber}"]`);
    if (questionElement) {
        questionElement.scrollIntoView({ 
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// 回答状況のハイライト
function highlightAnsweredQuestions() {
    const questions = document.querySelectorAll('.question-card');
    questions.forEach((question, index) => {
        const radioButtons = question.querySelectorAll('input[type="radio"]');
        const isAnswered = Array.from(radioButtons).some(radio => radio.checked);
        
        if (isAnswered) {
            question.classList.add('answered');
        } else {
            question.classList.remove('answered');
        }
    });
}

// ラジオボタンの変更を監視
document.addEventListener('change', function(e) {
    if (e.target.type === 'radio') {
        highlightAnsweredQuestions();
    }
});