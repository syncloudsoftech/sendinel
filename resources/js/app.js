import './bootstrap';
import $ from 'jquery';
import { sprintf } from 'sprintf-js';

const xhr = $.ajaxSettings.xhr;
$.ajaxSetup({
    progressDown() {},
    progressUp() {},
    xhr() {
        const request = xhr(), context = this;
        if (request.addEventListener) {
            request.addEventListener("progress", function (event) {
                context.progressDown(event);
            }, false);
        }

        if (request.upload && request.upload.addEventListener) {
            request.upload.addEventListener("progress", function (event) {
                context.progressUp(event);
            }, false);
        }

        return request;
    }
});

$('#form-download').on('submit', function (e) {
    const $form = $(this);
    const $submit = $form.find('button:submit');
    const time = parseInt($submit.data('wait-time'));
    if (time <= 0) return;

    e.preventDefault();
    const $i = $submit.find('i');
    const $span = $submit.find('span');
    const text = $submit.data('wait-text');
    const text_original = $span.text();
    $i.removeClass('fa-download')
        .addClass('fa-circle-notch fa-spin');
    $span.text(sprintf(text, time));
    $submit.prop('disabled', true);
    let waited = 0;
    const timer = setInterval(() => {
        if (waited < time) {
            $span.text(sprintf(text, time - (++waited)));
            return;
        }

        clearInterval(timer);
        $i.removeClass('fa-circle-notch fa-spin')
            .addClass('fa-download');
        $span.text(text_original);
        $submit.prop('disabled', false);
        $form.off('submit').submit();
    }, 1000);
});

$('#form-upload').on('submit', function (e) {
    e.preventDefault();
    const $form = $(this);
    const $submit = $form.find('button:submit');
    const $i = $submit.find('i');
    const $span = $submit.find('span');
    const text = $submit.data('wait-text');
    const text_original = $span.text();
    let progress = 0;
    $i.removeClass('fa-upload')
        .addClass('fa-circle-notch fa-spin');
    $span.text(sprintf(text, progress));
    $submit.prop('disabled', true);
    $form.find('.is-invalid').removeClass('is-invalid');
    $form.find('.invalid-feedback').remove();
    $.ajax({
        cache: false,
        complete() {
            $i.removeClass('fa-circle-notch fa-spin')
                .addClass('fa-upload');
            $span.text(text_original);
            $submit.prop('disabled', false);
        },
        contentType: false,
        data: new FormData($form.get(0)),
        error(xhr) {
            if (xhr.status !== 422) return;

            const errors = xhr.responseJSON.errors;
            for (const field in errors) {
                if (errors.hasOwnProperty(field)) {
                    $form.find(`[name="${field}"]`)
                        .addClass('is-invalid')
                        .after(
                            $('<div class="invalid-feedback"></div>')
                                .text(errors[field][0])
                        );
                }
            }
        },
        processData: false,
        progressUp(event) {
            const percent = (event.loaded / event.total) * 100;
            progress = Math.round(percent);
            $span.text(sprintf(text, progress));
        },
        success(response) {
            setTimeout(() => location.href = response.redirect, 50);
        },
        type: $form.attr('method'),
        url: $form.attr('action'),
    });
});

$('#button-copy-link').on('click', async function (e) {
    e.preventDefault();
    const $input = $(this).parent().find('input');
    let success;
    if (navigator.clipboard) {
        success = await navigator.clipboard.writeText($input.val())
            .then(() => true);
    } else {
        $input.focus();
        $input.select();
        success = document.execCommand('copy');
    }

    if (success) {
        $input.addClass('is-valid');
        setTimeout(() => $input.removeClass('is-valid'), 1000);
    }
});
