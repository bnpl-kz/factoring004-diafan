$(document).ready(function () {
    const description = 'Купи сейчас, плати потом! Быстрое и удобное оформление рассрочки на 4 месяца без первоначальной оплаты для жителей Казахстана. Моментальное подтверждение, без комиссий и процентов. Для заказов суммой от 6000 до 200000 тг.'
    const select = $('select[name="backend"]');
    setDefaultValue(select.val())

    $(select).on('change', function (e) {
        setDefaultValue(e.target.value)
    })

    function setDefaultValue(value) {
        if (value === 'factoring004') {
            $('textarea[name="text"]').val(description)
        } else {
            $('textarea[name="text"]').val('')
        }

    }
})
