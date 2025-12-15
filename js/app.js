$(document).foundation();

// Password strength indicator
$(document).ready(function() {
    // Add password strength indicator if password field exists
    if ($('input[name="password"]').length) {
        const passwordInput = $('input[name="password"]');
        const passwordRequirements = $('.password-requirements');
        
        // Create strength indicator
        const strengthIndicator = $('<div class="password-strength-indicator"></div>');
        const strengthBar = $('<div class="strength-bar"></div>');
        const strengthText = $('<div class="strength-text"></div>');
        
        strengthIndicator.append(strengthBar);
        strengthIndicator.append(strengthText);
        passwordRequirements.after(strengthIndicator);
        
        passwordInput.on('keyup', function() {
            const password = $(this).val();
            const strength = checkPasswordStrength(password);
            
            // Update strength bar
            strengthBar.removeClass('weak medium strong very-strong');
            strengthBar.addClass(strength.class);
            strengthBar.css('width', strength.width);
            strengthText.text(strength.text);
        });
    }
    
    // Form animation on submit
    $('#registrationForm').on('submit', function() {
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true);
        submitBtn.find('.button-text').text('Processing...');
        
        // Re-enable after 5 seconds (in case of error)
        setTimeout(function() {
            submitBtn.prop('disabled', false);
            submitBtn.find('.button-text').text('Join the Battle');
        }, 5000);
    });
    
    // Add fade-in animation to callouts
    $('.callout').hide().fadeIn(500);
    
    // Username validation on blur
    $('input[name="username"]').on('blur', function() {
        const username = $(this).val();
        if (username.length > 0 && username.length < 3) {
            $(this).addClass('invalid');
        } else if (!/^[a-zA-Z0-9]+$/.test(username)) {
            $(this).addClass('invalid');
        } else {
            $(this).removeClass('invalid');
        }
    });
    
    // Email validation on blur
    $('input[name="email"]').on('blur', function() {
        const email = $(this).val();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email.length > 0 && !emailRegex.test(email)) {
            $(this).addClass('invalid');
        } else {
            $(this).removeClass('invalid');
        }
    });
});

// Check password strength
function checkPasswordStrength(password) {
    let strength = 0;
    let text = '';
    let cssClass = '';
    let width = '0%';
    
    if (password.length === 0) {
        return { text: '', class: '', width: '0%' };
    }
    
    // Length check
    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    
    // Character variety checks
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;
    
    // Determine strength level
    if (strength <= 2) {
        text = 'Weak';
        cssClass = 'weak';
        width = '25%';
    } else if (strength <= 4) {
        text = 'Medium';
        cssClass = 'medium';
        width = '50%';
    } else if (strength <= 5) {
        text = 'Strong';
        cssClass = 'strong';
        width = '75%';
    } else {
        text = 'Very Strong';
        cssClass = 'very-strong';
        width = '100%';
    }
    
    return { text: text, class: cssClass, width: width };
}
