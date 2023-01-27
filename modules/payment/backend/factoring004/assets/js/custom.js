$(document).ready(function () {
    const description = 'Купи сейчас, плати потом! Быстрое и удобное оформление рассрочки на 4 месяца без первоначальной оплаты. Моментальное подтверждение, без комиссий и процентов. Для заказов суммой от 6000 до 200000 тг.'
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

    $('#factoring004-agreement-button').on('click',function () {
        $('#factoring004_offer_file').click();
        $(document).on('change','#factoring004_offer_file', function (e) {
            let fd = new FormData();
            let files = $(e.target.files);
            if (files.length > 0) {
                fd.append('file',files[0]);
                $.ajax({
                    url: '/payment/get/factoring004/file-handler/upload',
                    data: fd,
                    method: 'post',
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('.btn_save').prop('disabled', true);
                    },
                    success: function(res) {
                        let data = $.parseJSON(res);
                        if (data.success) {
                            $('#factoring004_offer_file_name').val(data.filename)
                            $('#factoring004-agreement-button').prop('disabled',true)
                            alert(data.message)
                        } else {
                            alert(data.message)
                        }
                    },
                    error: function (e) {
                        alert(e.message)
                    },
                    complete: function () {
                        $('.btn_save').prop('disabled', false);
                    }
                });
            }
        })
    })

    $(document).on('click', '#factoring004-agreement-file-remove', function () {
        let filename = $('#factoring004-agreement-file-remove').data('value');
        $.ajax({
            url: '/payment/get/factoring004/file-handler/destroy',
            data: {filename: filename},
            method: 'post',
            beforeSend: function () {
                $('.btn_save').prop('disabled', true);
            },
            success: function(res) {
                let data = $.parseJSON(res);
                if (data.success) {
                    $('#factoring004_offer_file_name').val('')
                    $('#factoring004-agreement-file-remove').prop('disabled', true);
                    $('.agreement-link').removeAttr('href')
                    alert(data.message)
                } else {
                    alert(data.message)
                }
            },
            error: function (e) {
                alert(e.message)
            },
            complete: function () {
                $('.btn_save').prop('disabled', false);
            }
        });
    })
})
