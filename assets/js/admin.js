jQuery(document).ready(function($) {
    $('.select2').select2({
        theme: 'bootstrap-5'
    });

    // Attach change event handlers using jQuery
    $('#currency, #coin').on('change', updateShortcodeExample);

    // Call the function initially to update the shortcode example
    updateShortcodeExample();

    // Attach click event handler for copying shortcode
    $('#copyShortcodeBtn').on('click', copyShortcode);

    function updateShortcodeExample() {
        var selectedCurrency = $('#currency').val() || 'USD';
        var selectedCoin = $('#coin').val() || 'BTC';
        $('#shortcode-example').val('[crypcode convert="' + selectedCurrency + '" coin="' + selectedCoin + '"]');
    }

    function copyShortcode() {
        var copyText = $('#shortcode-example');
        var tempTextArea = $('<textarea>');
        tempTextArea.val(copyText.val());
        $('body').append(tempTextArea);
        tempTextArea.select();
        tempTextArea[0].setSelectionRange(0, 99999);
        document.execCommand('copy');
        tempTextArea.remove();
        alert('Shortcode copied to clipboard: ' + copyText.val());
    }
});
