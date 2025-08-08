/**
 * Comprehensive Form Validation Library for Clinic Appointment System
 * Provides reusable validation functions and utilities
 */

class FormValidator {
  constructor() {
    this.errors = [];
  }

  // Reset errors
  reset() {
    this.errors = [];
    return this;
  }

  // Add error to collection
  addError(field, message) {
    this.errors.push({ field, message });
    return this;
  }

  // Get all errors
  getErrors() {
    return this.errors;
  }

  // Check if validation passed
  isValid() {
    return this.errors.length === 0;
  }

  // Validation Rules
  static validateRequired(value, fieldName) {
    if (!value || (typeof value === 'string' && !value.trim())) {
      return `${fieldName} is required`;
    }
    return null;
  }

  static validateMinLength(value, minLength, fieldName) {
    if (value && value.length < minLength) {
      return `${fieldName} must be at least ${minLength} characters long`;
    }
    return null;
  }

  static validateMaxLength(value, maxLength, fieldName) {
    if (value && value.length > maxLength) {
      return `${fieldName} must be less than ${maxLength} characters`;
    }
    return null;
  }

  static validatePattern(value, pattern, fieldName, errorMessage) {
    if (value && !pattern.test(value)) {
      return errorMessage || `${fieldName} format is invalid`;
    }
    return null;
  }

  static validateEmail(email) {
    if (!email || !email.trim()) {
      return 'Email is required';
    }
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      return 'Please enter a valid email address';
    }
    return null;
  }

  static validatePhone(phone) {
    if (!phone || !phone.trim()) {
      return 'Phone number is required';
    }
    const phoneRegex = /^[\+]?[\d\s\-\(\)]+$/;
    if (!phoneRegex.test(phone)) {
      return 'Please enter a valid phone number';
    }
    const digitsOnly = phone.replace(/\D/g, '');
    if (digitsOnly.length < 10) {
      return 'Phone number must have at least 10 digits';
    }
    return null;
  }

  static validateName(name, fieldName) {
    let error = FormValidator.validateRequired(name, fieldName);
    if (error) return error;

    error = FormValidator.validateMinLength(name, 2, fieldName);
    if (error) return error;

    error = FormValidator.validateMaxLength(name, 50, fieldName);
    if (error) return error;

    error = FormValidator.validatePattern(
      name, 
      /^[A-Za-z\s]+$/, 
      fieldName, 
      `${fieldName} should only contain letters and spaces`
    );
    if (error) return error;

    return null;
  }

  static validateDate(date, fieldName, options = {}) {
    if (!date) {
      return `${fieldName} is required`;
    }

    const inputDate = new Date(date);
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    if (options.mustBePast && inputDate >= today) {
      return `${fieldName} must be in the past`;
    }

    if (options.mustBeFuture && inputDate <= today) {
      return `${fieldName} must be in the future`;
    }

    if (options.minAge) {
      const maxBirthDate = new Date(today.getFullYear() - options.minAge, today.getMonth(), today.getDate());
      if (inputDate > maxBirthDate) {
        return `Age must be at least ${options.minAge} years`;
      }
    }

    if (options.maxAge) {
      const minBirthDate = new Date(today.getFullYear() - options.maxAge, today.getMonth(), today.getDate());
      if (inputDate < minBirthDate) {
        return `Age cannot exceed ${options.maxAge} years`;
      }
    }

    if (options.weekdaysOnly) {
      // Parse date more explicitly to avoid timezone issues
      const dateParts = date.split('-');
      const parsedDate = new Date(parseInt(dateParts[0]), parseInt(dateParts[1]) - 1, parseInt(dateParts[2]));
      const dayOfWeek = parsedDate.getDay();
      console.log('Validating weekday - Date:', date, 'Day of week:', dayOfWeek, 'Parsed date:', parsedDate);
      if (dayOfWeek === 0 || dayOfWeek === 6) {
        return `${fieldName} must be a weekday (Monday-Friday)`;
      }
    }

    if (options.minDaysFromToday) {
      const minDate = new Date(today);
      minDate.setDate(minDate.getDate() + options.minDaysFromToday);
      if (inputDate < minDate) {
        return `${fieldName} must be at least ${options.minDaysFromToday} days from today`;
      }
    }

    if (options.maxDaysFromToday) {
      const maxDate = new Date(today);
      maxDate.setDate(maxDate.getDate() + options.maxDaysFromToday);
      if (inputDate > maxDate) {
        return `${fieldName} cannot be more than ${options.maxDaysFromToday} days from today`;
      }
    }

    return null;
  }

  static validateInsuranceId(insuranceId) {
    let error = FormValidator.validateRequired(insuranceId, 'Insurance ID');
    if (error) return error;

    error = FormValidator.validateMinLength(insuranceId, 5, 'Insurance ID');
    if (error) return error;

    error = FormValidator.validatePattern(
      insuranceId, 
      /^[A-Za-z0-9\-]+$/, 
      'Insurance ID', 
      'Insurance ID should only contain letters, numbers, and hyphens'
    );
    if (error) return error;

    return null;
  }

  static validatePatientId(patientId) {
    let error = FormValidator.validateRequired(patientId, 'Patient ID');
    if (error) return error;

    error = FormValidator.validatePattern(
      patientId, 
      /^\d+$/, 
      'Patient ID', 
      'Patient ID should only contain numbers'
    );
    if (error) return error;

    return null;
  }

  // UI Helper Functions
  static showFieldError(fieldId, errorId, message) {
    const field = document.getElementById(fieldId);
    const errorElement = document.getElementById(errorId);
    
    if (!field || !errorElement) return;

    if (message) {
      field.classList.add('field-error');
      errorElement.textContent = message;
      errorElement.style.display = 'block';
    } else {
      field.classList.remove('field-error');
      errorElement.textContent = '';
      errorElement.style.display = 'none';
    }
  }

  static showValidationSummary(summaryId, errors, isSuccess = false) {
    const summary = document.getElementById(summaryId);
    if (!summary) return;
    
    if (errors.length > 0) {
      const errorMessages = errors.map(error => 
        typeof error === 'string' ? error : error.message
      );
      summary.innerHTML = '<strong>Please fix the following errors:</strong><br>' + errorMessages.join('<br>');
      summary.className = 'validation-summary';
      summary.style.display = 'block';
    } else if (isSuccess) {
      summary.innerHTML = '<strong>All fields are valid!</strong>';
      summary.className = 'validation-summary success';
      summary.style.display = 'block';
    } else {
      summary.style.display = 'none';
    }
  }

  static addRealTimeValidation(fieldId, validationFunction, errorId) {
    const field = document.getElementById(fieldId);
    if (!field) return;

    field.addEventListener('blur', function() {
      const error = validationFunction(this.value);
      FormValidator.showFieldError(fieldId, errorId, error);
    });

    field.addEventListener('input', function() {
      // Clear error on input if field had error
      if (this.classList.contains('field-error')) {
        const error = validationFunction(this.value);
        if (!error) {
          FormValidator.showFieldError(fieldId, errorId, null);
        }
      }
    });
  }

  // Character counter utility
  static addCharacterCounter(fieldId, counterId, maxLength) {
    const field = document.getElementById(fieldId);
    const counter = document.getElementById(counterId);
    
    if (!field || !counter) return;

    field.addEventListener('input', function() {
      const currentLength = this.value.length;
      counter.textContent = currentLength;
      
      if (currentLength > maxLength) {
        counter.style.color = '#d32f2f';
        this.classList.add('field-error');
      } else if (currentLength > maxLength * 0.8) {
        counter.style.color = '#ff9800';
        this.classList.remove('field-error');
      } else {
        counter.style.color = '#666';
        this.classList.remove('field-error');
      }
    });
  }

  // Appointment-specific validations
  static validateAppointmentDate(date) {
    return FormValidator.validateDate(date, 'Appointment date', {
      mustBeFuture: true,
      weekdaysOnly: true,
      minDaysFromToday: 1,
      maxDaysFromToday: 90
    });
  }

  static validateAppointmentTime(time, date) {
    if (!time) {
      return 'Appointment time is required';
    }
    
    if (!date) {
      return null; // Date validation will catch this
    }
    
    const selectedDate = new Date(date);
    const today = new Date();
    
    // If appointment is tomorrow, check if time has passed
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    if (selectedDate.toDateString() === tomorrow.toDateString()) {
      const [hours, minutes] = time.split(':');
      const appointmentTime = new Date(selectedDate);
      appointmentTime.setHours(parseInt(hours), parseInt(minutes), 0, 0);
      
      const currentTime = new Date();
      if (appointmentTime <= currentTime) {
        return 'Please select a future time for tomorrow\'s appointment';
      }
    }
    
    return null;
  }
}

// Export for use in other files
if (typeof module !== 'undefined' && module.exports) {
  module.exports = FormValidator;
}

// Global availability
window.FormValidator = FormValidator;
