function redirectToDetail(link) {
    window.location.href = link;
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.minus').forEach(function(minusBtn) {
        minusBtn.addEventListener('click', function() {
            var input = this.parentElement.querySelector('input[name="quantity"]');
            var count = parseInt(input.value) - 1;
            count = count < 1 ? 1 : count;
            input.value = count;
            input.dispatchEvent(new Event('change'));
            return false;
        });
    });

    document.querySelectorAll('.plus').forEach(function(plusBtn) {
        plusBtn.addEventListener('click', function() {
            var input = this.parentElement.querySelector('input[name="quantity"]');
            input.value = parseInt(input.value) + 1;
            input.dispatchEvent(new Event('change'));
            return false;
        });
    });
});

  