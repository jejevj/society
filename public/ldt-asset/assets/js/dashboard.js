$(document).ready(function() {
    loadDashboard();
    loadListData();
    loadTautan();
    loadSlider();
});

function loadDashboard() {
    $.ajax({
        url: APP_URLS.count,
        type: "GET",
        success: function(res) {
            $('#organisasi_count').text(res.organisasi_count);
            $('#dataset_count').text(res.dataset_count);
            $('#infografis_count').text(res.infografis_count);
            $('#total_data').text(res.total_data);
        }
    });
}

function loadListData() {
    fetch(APP_URLS.list)
        .then(res => res.json())
        .then(res => {
            document.getElementById('dataset_container').innerHTML = res.dataset_html;
            document.getElementById('infografis_container').innerHTML = res.infografis_html;
        });
}


function loadTautan() {
    fetch(APP_URLS.tautan)
        .then(res => res.json())
        .then(res => {
            document.getElementById('tautan_container').innerHTML = res.html;
        })
        .catch(() => {
            document.getElementById('tautan_container').innerHTML = `
                <div class="text-center py-5 w-100">
                    <span class="text-danger">Gagal memuat tautan..</span>
                </div>
            `;
        });
}

function loadSlider() {
    fetch(APP_URLS.slider)
        .then(res => res.json())
        .then(res => {
            document.getElementById('slider_container').innerHTML = res.html;

            new bootstrap.Carousel(document.querySelector('#heroSlider'), {
                interval: 4000,
                ride: 'carousel'
            });
        });
}