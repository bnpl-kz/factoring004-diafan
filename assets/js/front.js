//start-factoring004
$(document).ready(function () {
    const min_total = 6000;
    const max_total = 200000;
    const factoring004_payment_id = $(".paymethod-d__name:contains('Рассрочка 0-0-4')").parent().parent().find('input').val();
    let button_submit = $('.js_form_order').find('button[type="submit"]');
    button_submit.prop('disabled', true)

    $(document).ajaxStart(function () {
        button_submit.prop('disabled', true)
    })

    $(document).ajaxStop(function() {
        payment();
        button_submit.prop('disabled', false)
    });

    $(document).on('click','#factoring004-button',function () {
        if (!$('#factoring004-offer-agreement').is('checked')) {
            alert('Вы должны согласиться с условиями Рассрочка 0-0-4')
        }
    })

    $(document).on('change','#factoring004-offer-agreement',function () {
        if (this.checked) {
            button_submit.css('display','block')
            $('#factoring004-button').remove()
        } else {
            button_submit.after('<button id="factoring004-button" class="button-d" type="button">Продолжить</button>')
            button_submit.css('display','none')
        }
    })

    function payment()
    {
        let current_payment_id = $('input[name=payment_id]:checked').val();
        let total_sum = $('.cell-d_total').children()[0].children[0].textContent;
        if (total_sum < min_total) {
            if (!$('.min-sum').length) {
                $(`#payment${factoring004_payment_id}`).prop('checked', false).prop('disabled',true).parent().next().append(`<div style="padding-top: 5px; color: #ee2f2f;" class="field-d min-sum">Минимальная сумма покупки в рассрочку 6000 Тенге. Не хватает ${min_total - total_sum} тенге</div>`);
            }
        }
        if (total_sum > max_total) {
            if (!$('.max-sum').length) {
                $(`#payment${factoring004_payment_id}`).prop('checked', false).prop('disabled', true).parent().next().append(`<div style="padding-top: 5px; color: #ee2f2f;" class="field-d max-sum">Максимальная сумма покупки в рассрочку 200000 Тенге. Сумма превышает ${total_sum - max_total} тенге</div>`);
            }
        }
        if (factoring004_payment_id === current_payment_id) {
            button_submit.after('<button id="factoring004-button" class="button-d" type="button">Продолжить</button>')
            button_submit.css('display','none')
            if (!$('.agreement-file').length) {
                $(`#payment${factoring004_payment_id}`).parent().next().append('<div style="padding-top: 5px;" class="field-d agreement-file"><input type="checkbox" id="factoring004-offer-agreement"><label for="factoring004-offer-agreement">Я ознакомлен и согласен с условиями <a target="_blank" href="">Рассрочка 0-0-4</a></label></div>');
            }
        } else {
            button_submit.css('display','block')
            $('#factoring004-button').remove()
        }
    }
})
//end-factoring004