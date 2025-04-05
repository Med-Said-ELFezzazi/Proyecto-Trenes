document.addEventListener("DOMContentLoaded", function () {
    const tabs = document.querySelectorAll('#authTabs button[data-bs-toggle="tab"]');
    const storedTabId = localStorage.getItem("activeTab");

    // Si hay una pestaña activa guardada, activarla
    if (storedTabId) {
        const tabToActivate = document.querySelector(`#authTabs button[data-bs-target="${storedTabId}"]`);
        if (tabToActivate) {
            // Activar la pestaña correspondiente
            new bootstrap.Tab(tabToActivate).show();
        }
    }

    // Guardar la pestaña activa al cambiar de tab
    tabs.forEach(tab => {
        tab.addEventListener("shown.bs.tab", function (event) {
            const activeTabId = event.target.dataset.bsTarget;
            localStorage.setItem("activeTab", activeTabId);
        });
    });
});
