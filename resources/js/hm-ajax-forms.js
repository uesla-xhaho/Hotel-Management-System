const DEFAULT_SUCCESS_MESSAGE = 'Saved successfully.';
const DEFAULT_ERROR_MESSAGE = 'Something went wrong. Please try again.';
const VALIDATION_MESSAGE = 'Please check the highlighted fields.';
const MESSAGE_TIMEOUT_MS = 4000;

function getFeedbackContainer() {
    let container = document.getElementById('hm-feedback-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'hm-feedback-container';
        container.setAttribute('aria-live', 'polite');
        container.setAttribute('aria-atomic', 'true');
        container.style.position = 'fixed';
        container.style.top = '96px';
        container.style.right = '12px';
        container.style.zIndex = '1090';
        container.style.width = 'min(560px, calc(100vw - 24px))';
        container.style.display = 'flex';
        container.style.flexDirection = 'column';
        container.style.gap = '10px';
        document.body.appendChild(container);
    }
    return container;
}

function dismissMessage(alert) {
    if (!alert || alert.dataset.hmDismissed === '1') {
        return;
    }

    alert.dataset.hmDismissed = '1';
    alert.classList.add('hm-alert-hiding');
    alert.style.opacity = '0';
    alert.style.transform = 'translateY(-6px)';

    window.setTimeout(() => {
        if (alert.parentElement) {
            alert.remove();
        }
    }, 350);
}

function showMessage(type, message) {
    if (!message) {
        return;
    }

    const container = getFeedbackContainer();
    container.querySelectorAll('.hm-feedback-alert').forEach((existing) => existing.remove());

    const alert = document.createElement('div');
    const variant = type === 'error' ? 'danger' : type;

    alert.className = `hm-feedback-alert alert alert-${variant} alert-dismissible fade show`;
    alert.setAttribute('role', 'alert');
    alert.style.margin = '0';
    alert.style.transition = 'opacity 0.35s ease, transform 0.35s ease';
    alert.textContent = message;

    const closeButton = document.createElement('button');
    closeButton.type = 'button';
    closeButton.className = 'btn-close';
    closeButton.setAttribute('data-bs-dismiss', 'alert');
    closeButton.setAttribute('aria-label', 'Close');
    closeButton.addEventListener('click', () => dismissMessage(alert));
    alert.appendChild(closeButton);

    container.appendChild(alert);

    window.setTimeout(() => {
        dismissMessage(alert);
    }, MESSAGE_TIMEOUT_MS);
}

function showInitialValidationMessage() {
    const candidates = Array.from(document.querySelectorAll('.invalid-feedback'));
    let firstMessage = '';

    candidates.forEach((el) => {
        const text = (el.textContent || '').trim();
        if (!firstMessage && text.length > 0) {
            firstMessage = text;
        }

        if (text.length > 0) {
            el.classList.add('d-none');
            el.style.display = 'none';
        }
    });

    if (firstMessage) {
        showMessage('error', firstMessage);
    }
}

function setLoading(form, loading) {
    form.classList.toggle('hm-form-loading', loading);
    form.setAttribute('aria-busy', loading ? 'true' : 'false');

    const submitters = form.querySelectorAll('button[type="submit"], input[type="submit"]');
    submitters.forEach((button) => {
        const isInput = button.tagName === 'INPUT';
        const textProp = isInput ? 'value' : 'innerHTML';
        if (loading) {
            button.dataset.hmOriginalText = button[textProp];
            button.disabled = true;
            if (button.dataset.hmLoadingText) {
                button[textProp] = button.dataset.hmLoadingText;
            }
        } else {
            button.disabled = false;
            if (button.dataset.hmOriginalText) {
                button[textProp] = button.dataset.hmOriginalText;
                delete button.dataset.hmOriginalText;
            }
        }
    });
}

function clearErrors(form) {
    form.querySelectorAll('.is-invalid').forEach((el) => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach((el) => {
        el.textContent = '';
        if (el.dataset.hmError === 'true') {
            el.remove();
        }
    });
}

function escapeSelector(value) {
    if (window.CSS && window.CSS.escape) {
        return window.CSS.escape(value);
    }
    return value.replace(/["\\]/g, '\\$&');
}

function applyErrors(form, errors) {
    let firstMessage = '';
    if (!errors) {
        return firstMessage;
    }

    Object.keys(errors).forEach((fieldName) => {
        const messages = errors[fieldName];
        const field = form.querySelector(`[name="${escapeSelector(fieldName)}"]`);
        if (!field) {
            return;
        }

        field.classList.add('is-invalid');
        const message = Array.isArray(messages) ? messages[0] : String(messages);
        if (!firstMessage) {
            firstMessage = message;
        }

        const feedback = field.parentElement ? field.parentElement.querySelector('.invalid-feedback') : null;
        if (feedback) {
            feedback.textContent = '';
            feedback.classList.add('d-none');
            feedback.style.display = 'none';
        }
    });

    return firstMessage;
}

function destroyDataTables(root) {
    if (!root || !window.$ || !$.fn || !$.fn.dataTable) {
        return;
    }

    root.querySelectorAll('table[data-hm-datatable="true"]').forEach((table) => {
        if ($.fn.dataTable.isDataTable(table)) {
            $(table).DataTable().destroy();
        }
    });
}

async function refreshTargets(selectorList) {
    if (!selectorList) {
        return;
    }

    const selectors = selectorList
        .split(',')
        .map((value) => value.trim())
        .filter(Boolean);

    if (!selectors.length) {
        return;
    }

    const response = await fetch(window.location.href, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });

    if (!response.ok) {
        return;
    }

    const html = await response.text();
    const doc = new DOMParser().parseFromString(html, 'text/html');

    selectors.forEach((selector) => {
        const current = document.querySelector(selector);
        const fresh = doc.querySelector(selector);
        if (!current || !fresh) {
            return;
        }

        destroyDataTables(current);
        current.innerHTML = fresh.innerHTML;
    });

    if (window.hmInitTables) {
        window.hmInitTables(document);
    }

    if (window.hmInitSwiper) {
        window.hmInitSwiper();
    }
}

function closeModal(selector, form) {
    if (!selector) {
        return;
    }

    let target = null;
    if (selector === 'closest' && form) {
        target = form.closest('.modal');
    } else {
        target = document.querySelector(selector);
    }

    if (!target) {
        return;
    }

    if (window.bootstrap && window.bootstrap.Modal) {
        const instance = window.bootstrap.Modal.getInstance(target) || new window.bootstrap.Modal(target);
        instance.hide();
        return;
    }

    if (window.$) {
        window.$(target).modal('hide');
    }
}

async function handleFormSubmit(form, submitter) {
    const action = form.getAttribute('action');
    if (!action || !window.axios) {
        return;
    }

    clearErrors(form);
    setLoading(form, true);

    const formData = new FormData(form);
    if (submitter && submitter.name) {
        formData.set(submitter.name, submitter.value || '1');
    }

    try {
        const response = await window.axios({
            method: (form.getAttribute('method') || 'POST').toUpperCase(),
            url: action,
            data: formData,
            headers: {
                Accept: 'application/json'
            }
        });

        const redirectUrl = response.data && response.data.redirect;
        if (redirectUrl) {
            window.location.href = redirectUrl;
            return;
        }

        const message = (response.data && response.data.message) || form.dataset.hmSuccessMessage || DEFAULT_SUCCESS_MESSAGE;
        showMessage('success', message);

        if (form.dataset.hmCloseModal) {
            closeModal(form.dataset.hmCloseModal, form);
        }

        if (form.dataset.hmReset === 'true') {
            form.reset();
        }

        if (form.dataset.hmRefresh) {
            await refreshTargets(form.dataset.hmRefresh);
        }
    } catch (error) {
        const response = error.response;
        if (response && response.status === 422) {
            const firstError = applyErrors(form, response.data && response.data.errors);
            showMessage('error', firstError || response.data.message || VALIDATION_MESSAGE);
        } else {
            const message = (response && response.data && response.data.message) || DEFAULT_ERROR_MESSAGE;
            showMessage('error', message);
        }
    } finally {
        setLoading(form, false);
    }
}

document.addEventListener('submit', (event) => {
    const form = event.target;
    if (!(form instanceof HTMLFormElement)) {
        return;
    }

    if (form.dataset.hmAjax === 'false') {
        return;
    }

    if (!window.axios) {
        return;
    }

    const method = (form.getAttribute('method') || 'GET').toUpperCase();
    if (method === 'GET') {
        return;
    }

    event.preventDefault();
    handleFormSubmit(form, event.submitter);
});

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', showInitialValidationMessage);
} else {
    showInitialValidationMessage();
}
