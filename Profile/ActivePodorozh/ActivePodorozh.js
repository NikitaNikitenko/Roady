document.getElementById('downloadBtn').addEventListener('click', function() {
    const link = document.createElement('a');
    link.href = document.getElementById('ticketImage').src;
    link.download = 'https://static.vecteezy.com/system/resources/previews/018/931/148/original/cartoon-ticket-icon-png.png';
    link.click();
});
