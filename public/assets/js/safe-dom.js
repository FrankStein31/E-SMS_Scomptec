// Global JavaScript Utilities
// Safe DOM manipulation functions to prevent null pointer errors

// Safe querySelector wrapper
function safeQuerySelector(selector) {
    try {
        return document.querySelector(selector);
    } catch (e) {
        console.warn('SafeQuerySelector error:', e);
        return null;
    }
}

// Safe querySelectorAll wrapper
function safeQuerySelectorAll(selector) {
    try {
        return document.querySelectorAll(selector) || [];
    } catch (e) {
        console.warn('SafeQuerySelectorAll error:', e);
        return [];
    }
}

// Safe addEventListener wrapper
function safeAddEventListener(element, event, handler) {
    if (element && typeof element.addEventListener === 'function') {
        element.addEventListener(event, handler);
        return true;
    }
    return false;
}

// Safe element existence checker
function elementExists(selector) {
    return document.querySelector(selector) !== null;
}

// Safe class manipulation
function safeToggleClass(selector, className) {
    const element = safeQuerySelector(selector);
    if (element) {
        element.classList.toggle(className);
        return true;
    }
    return false;
}

function safeAddClass(selector, className) {
    const element = safeQuerySelector(selector);
    if (element) {
        element.classList.add(className);
        return true;
    }
    return false;
}

function safeRemoveClass(selector, className) {
    const element = safeQuerySelector(selector);
    if (element) {
        element.classList.remove(className);
        return true;
    }
    return false;
}

// Safe forEach for classList
function safeForEachClass(element, callback) {
    if (element && element.classList && typeof element.classList.forEach === 'function') {
        try {
            element.classList.forEach(callback);
            return true;
        } catch (e) {
            console.warn('SafeForEachClass error:', e);
        }
    }
    return false;
}

// Safe jQuery element access
function safeJQueryElement(selector, index = 0) {
    try {
        const $elements = typeof $ !== 'undefined' ? $(selector) : null;
        if ($elements && $elements.length > index) {
            return $elements[index];
        }
    } catch (e) {
        console.warn('SafeJQueryElement error:', e);
    }
    return null;
}

// Safe CodeMirror initialization
function safeCodeMirror(elementId, options = {}) {
    try {
        if (typeof CodeMirror === 'undefined') {
            // Don't warn - CodeMirror might not be needed on this page
            return null;
        }
        
        const element = document.getElementById(elementId);
        if (!element) {
            // Don't warn - element might not exist on this page
            return null;
        }
        
        return CodeMirror.fromTextArea(element, options);
    } catch (e) {
        console.warn('SafeCodeMirror error:', e);
        return null;
    }
}

// Safe conditional initialization - only run if element exists
function safeConditionalInit(selector, callback) {
    try {
        const element = typeof selector === 'string' ? 
            document.querySelector(selector) || document.getElementById(selector) : 
            selector;
        
        if (element) {
            return callback(element);
        }
    } catch (e) {
        console.warn('SafeConditionalInit error:', e);
    }
    return null;
}

// Global error handler for uncaught JavaScript errors
window.addEventListener('error', function(e) {
    console.warn('JavaScript Error Caught:', e.message, 'at', e.filename + ':' + e.lineno);
    // Prevent the error from breaking the page
    return true;
});

// Export functions for global use
window.SafeDOM = {
    querySelector: safeQuerySelector,
    querySelectorAll: safeQuerySelectorAll,
    addEventListener: safeAddEventListener,
    exists: elementExists,
    toggleClass: safeToggleClass,
    addClass: safeAddClass,
    removeClass: safeRemoveClass,
    forEachClass: safeForEachClass,
    jQueryElement: safeJQueryElement,
    codeMirror: safeCodeMirror,
    conditionalInit: safeConditionalInit
};

console.log('SafeDOM utilities loaded successfully');
