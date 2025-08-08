<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ReceiptBook */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
.form-container {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid #e9ecef;
    max-width: 800px;
    margin: 0 auto;
}

.form-section {
    margin-bottom: 2rem;
}

.section-title {
    color: #495057;
    font-weight: 700;
    font-size: 1.2rem;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e9ecef;
}

.form-control {
    border-radius: 12px;
    border: 2px solid #e9ecef;
    padding: 0.875rem 1.25rem;
    transition: all 0.3s ease;
    font-weight: 500;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.75rem;
}

.form-check {
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-check:hover {
    background: #e9ecef;
}

.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

.form-check-label {
    font-weight: 600;
    color: #495057;
}

.btn-container {
    text-align: center;
    padding-top: 2rem;
    border-top: 2px solid #e9ecef;
}

.btn {
    border-radius: 12px;
    padding: 0.875rem 3rem;
    font-weight: 600;
    margin: 0 0.5rem;
    transition: all 0.3s ease;
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    box-shadow: 0 4px 20px rgba(40, 167, 69, 0.3);
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(40, 167, 69, 0.4);
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    border: none;
    box-shadow: 0 4px 20px rgba(108, 117, 125, 0.3);
}

.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(108, 117, 125, 0.4);
}

.help-text {
    background: #e3f2fd;
    border: 1px solid #2196f3;
    border-radius: 10px;
    padding: 1rem;
    margin-bottom: 2rem;
    color: #1976d2;
}

.input-group {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

@media (max-width: 768px) {
    .form-container {
        padding: 1rem;
        margin: 1rem;
    }
    
    .input-group {
        grid-template-columns: 1fr;
    }
    
    .btn-container .btn {
        display: block;
        width: 100%;
        margin: 0.5rem 0;
    }
}

.field-error {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25) !important;
}

.invalid-feedback {
    display: block;
    color: #dc3545;
    font-weight: 600;
    margin-top: 0.5rem;
}
</style>

<div class="receipt-book-form">
    <div class="form-container">
        
        <!-- Help Text -->
        <div class="help-text">
            <i class="bi bi-info-circle me-2"></i>
            <strong>คำแนะนำ:</strong> 
            กรอกข้อมูลเล่มใบเสร็จ เลขเริ่มต้นและเลขสิ้นสุดจะกำหนดช่วงของใบเสร็จในเล่มนี้
        </div>

        <?php $form = ActiveForm::begin([
            'id' => 'receipt-book-form',
            'options' => ['class' => 'needs-validation', 'novalidate' => true],
        ]); ?>

        <!-- Basic Information -->
        <div class="form-section">
            <h4 class="section-title">
                <i class="bi bi-journal me-2"></i>ข้อมูลพื้นฐาน
            </h4>
            
            <?= $form->field($model, 'book_number')->textInput([
                'maxlength' => true,
                'placeholder' => 'เช่น A001, B002',
                'class' => 'form-control'
            ])->label('เลขที่เล่มใบเสร็จ') ?>
        </div>

        <!-- Number Range -->
        <div class="form-section">
            <h4 class="section-title">
                <i class="bi bi-list-ol me-2"></i>ช่วงเลขใบเสร็จ
            </h4>
            
            <div class="input-group">
                <?= $form->field($model, 'start_number')->textInput([
                    'type' => 'number',
                    'min' => 1,
                    'placeholder' => '1',
                    'class' => 'form-control'
                ])->label('เลขเริ่มต้น') ?>

                <?= $form->field($model, 'end_number')->textInput([
                    'type' => 'number',
                    'min' => 1,
                    'placeholder' => '1000',
                    'class' => 'form-control'
                ])->label('เลขสิ้นสุด') ?>
            </div>

            <?php if (!$model->isNewRecord): ?>
                <?= $form->field($model, 'current_number')->textInput([
                    'type' => 'number',
                    'min' => $model->start_number,
                    'max' => $model->end_number,
                    'class' => 'form-control'
                ])->label('เลขปัจจุบัน') ?>
            <?php endif; ?>
        </div>

        <!-- Status -->
        <div class="form-section">
            <h4 class="section-title">
                <i class="bi bi-toggle-on me-2"></i>สถานะการใช้งาน
            </h4>
            
            <div class="form-check">
                <?= $form->field($model, 'is_active')->checkbox([
                    'class' => 'form-check-input',
                    'label' => false,
                ]) ?>
                <label class="form-check-label" for="receiptbook-is_active">
                    <i class="bi bi-check-circle me-2"></i>
                    <strong>เปิดใช้งานเล่มนี้ทันที</strong>
                    <br>
                    <small class="text-muted">หากเปิดใช้งาน เล่มอื่นจะถูกปิดการใช้งานอัตโนมัติ</small>
                </label>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="btn-container">
            <?= Html::submitButton($model->isNewRecord ? '<i class="bi bi-plus-circle me-2"></i>สร้างเล่มใหม่' : '<i class="bi bi-check-circle me-2"></i>บันทึกการแก้ไข', [
                'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success'
            ]) ?>
            
            <?= Html::a('<i class="bi bi-x-circle me-2"></i>ยกเลิก', ['index'], [
                'class' => 'btn btn-secondary'
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.getElementById('receipt-book-form');
    const startNumberInput = document.querySelector('#receiptbook-start_number');
    const endNumberInput = document.querySelector('#receiptbook-end_number');
    
    function validateNumbers() {
        const startNum = parseInt(startNumberInput.value);
        const endNum = parseInt(endNumberInput.value);
        
        if (startNum && endNum && endNum < startNum) {
            endNumberInput.classList.add('field-error');
            if (!document.querySelector('.number-error')) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback number-error';
                errorDiv.textContent = 'เลขสิ้นสุดต้องมากกว่าหรือเท่ากับเลขเริ่มต้น';
                endNumberInput.parentNode.appendChild(errorDiv);
            }
            return false;
        } else {
            endNumberInput.classList.remove('field-error');
            const errorDiv = document.querySelector('.number-error');
            if (errorDiv) {
                errorDiv.remove();
            }
            return true;
        }
    }
    
    startNumberInput.addEventListener('input', validateNumbers);
    endNumberInput.addEventListener('input', validateNumbers);
    
    form.addEventListener('submit', function(e) {
        if (!validateNumbers()) {
            e.preventDefault();
            e.stopPropagation();
        }
    });
    
    // Auto-calculate total receipts
    function updateInfo() {
        const startNum = parseInt(startNumberInput.value) || 0;
        const endNum = parseInt(endNumberInput.value) || 0;
        
        if (startNum && endNum && endNum >= startNum) {
            const total = endNum - startNum + 1;
            
            // แสดงข้อมูลเพิ่มเติม
            let infoDiv = document.querySelector('.receipt-info');
            if (!infoDiv) {
                infoDiv = document.createElement('div');
                infoDiv.className = 'receipt-info alert alert-info mt-2';
                endNumberInput.parentNode.appendChild(infoDiv);
            }
            
            infoDiv.innerHTML = `
                <i class="bi bi-info-circle me-2"></i>
                <strong>เล่มนี้จะมีใบเสร็จทั้งหมด ${total.toLocaleString()} ใบ</strong>
            `;
        } else {
            const infoDiv = document.querySelector('.receipt-info');
            if (infoDiv) {
                infoDiv.remove();
            }
        }
    }
    
    startNumberInput.addEventListener('input', updateInfo);
    endNumberInput.addEventListener('input', updateInfo);
    
    // Initial calculation
    updateInfo();
});
</script>
