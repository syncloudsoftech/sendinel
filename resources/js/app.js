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

$('button[data-action="copy-link"]').on('click', async function (e) {
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

$('button[data-action="download"]').on('click', function (e) {
    e.preventDefault();
    const $this = $(this);
    const $i = $('i', this);
    const $span = $('span', this);
    const text = $this.data('wait-text');
    const text_original = $span.text();
    const time = parseInt($this.data('wait-time'));
    $i.removeClass('fa-download')
        .addClass('fa-circle-notch fa-spin');
    $span.text(sprintf(text, time));
    $this.prop('disabled', true);
    let waited = 0;
    const timer = setInterval(() => {
        if (waited < time) {
            $span.text(sprintf(text, time - (++waited)))
            return
        }

        clearInterval(timer);
        $i.removeClass('fa-circle-notch fa-spin')
            .addClass('fa-download');
        $span.text(text_original);
        $this.prop('disabled', false);
        $this.closest('form').submit();
    }, 1000);
});

$('button[data-action="upload"]').on('click', function (e) {
    e.preventDefault();
    const $this = $(this);
    const $i = $('i', this);
    const $span = $('span', this);
    const text = $this.data('wait-text');
    const text_original = $span.text();
    let progress = 0;
    $i.removeClass('fa-upload')
        .addClass('fa-circle-notch fa-spin');
    $span.text(sprintf(text, progress));
    $this.prop('disabled', true);
    const $form = $this.closest('form');
    $form.find('.is-invalid').removeClass('is-invalid');
    $form.find('.invalid-feedback').remove();
    $.ajax({
        cache: false,
        complete() {
            $i.removeClass('fa-circle-notch fa-spin')
                .addClass('fa-upload');
            $span.text(text_original);
            $this.prop('disabled', false);
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
