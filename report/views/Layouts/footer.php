</main>
<script>
    const active = document.getElementById('active');
    const deactive = document.getElementById('deactive');

    // setInterval(() => {
    //     const params = new URLSearchParams();
    //     params.append('check_notification', 'check_notification');
    //     axios
    //         .post("./app/Controllers/notificationAjaxController.php", params)
    //         .then(function(response) {
    //             console.log(response.data);
    //             if (response.data > 0) {
    //                 active.classList.remove('hidden');
    //                 deactive.classList.add('hidden');
    //             } else {
    //                 deactive.classList.remove('hidden');
    //                 active.classList.add('hidden');
    //             }
    //         })
    //         .catch(function(error) {
    //             console.log(error);
    //         });
    // }, 30000);

    function toggleTV() {
        const params = new URLSearchParams();
        params.append('toggle', 'toggle');
        axios
            .post("./tvController.php", params)
            .then(function(response) {
                alert(response.data);
            })
            .catch(function(error) {
                console.log(error);
            });
    }
</script>
<script>
    const toggleNav = () => {
        const nav = document.getElementById("nav");
        if (nav.classList.contains("open")) {
            nav.classList.remove("open");
        } else {
            nav.classList.add("open");
        }
    };
</script>
</body>

</html>