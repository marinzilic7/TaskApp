<template>
    <div
        class="offcanvas offcanvas-start mt-5"
        tabindex="-1"
        id="offcanvasExample"
        aria-labelledby="offcanvasExampleLabel"
    >
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">TaskApp</h5>
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas"
                aria-label="Close"
            ></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <li class="list-items d-flex" @click="closeAccordation()">
                    <i class="bi bi-brightness-high ms-2"></i>
                    <RouterLink
                        class="ms-2 text-decoration-none text-dark"
                        to="/"
                        >Moj dan</RouterLink
                    >
                    <a class="number-items ms-auto text-decoration-none">{{
                        tasks.length
                    }}</a>
                </li>
                <li class="list-items d-flex mt-4">
                    <i class="acc-star bi bi-star ms-2"></i>
                    <RouterLink
                        to="/important"
                        class="ms-2 text-decoration-none text-dark"
                    >
                        Važno
                    </RouterLink>
                    <a class="number-items ms-auto text-decoration-none">{{
                        importantTask.length
                    }}</a>
                </li>
                <li class="list-items d-flex mt-4">
                    <i class="bi bi-calendar-check ms-2"></i>
                    <RouterLink
                        to="/planned"
                        class="ms-2 text-decoration-none text-dark"
                    >
                        Planirano
                    </RouterLink>
                    <a class="number-items ms-auto text-decoration-none">{{
                        unTasks.length
                    }}</a>
                </li>
                <li class="list-items d-flex mt-4">
                    <i class="bi bi-person ms-2"></i>
                    <RouterLink
                        to="/"
                        class="ms-2 text-decoration-none text-dark"
                    >
                        Timski rad
                    </RouterLink>
                    <a
                        class="number-items ms-auto text-decoration-none"
                        >{{
                    }}</a>
                </li>
                <hr />
                <div class="d-flex align-items-center">
                    <i class="bi bi-plus fs-3"></i>
                    <input
                        id="add_list"
                        class="border-0"
                        placeholder="Dodaj novi popis"
                        required
                    />
                    <button class="add_list_btn btn">
                        <i class="bi bi-check2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Sidebar",
    props: {
        tasks: {
            type: Array,
            required: true,
        },
        importantTask: {
            type: Array,
            required: true,
        },
        unTasks: {
            type: Array,
            required: true,
        },
    },
    mounted() {
        this.fixBodyOverflow();
    },
    methods: {
        closeAccordation() {
            const offcanvasElement =
                document.getElementById("offcanvasExample");

            if (offcanvasElement) {
                // Koristite Bootstrap Offcanvas API za zatvaranje
                const offcanvasInstance =
                    bootstrap.Offcanvas.getInstance(offcanvasElement);
                if (offcanvasInstance) {
                    offcanvasInstance.hide();
                }
            }
        },
        fixBodyOverflow() {
            // Pratite događaj kada se Offcanvas zatvori
            const offcanvasElement =
                document.querySelector("#offcanvasExample");
            offcanvasElement?.addEventListener("hidden.bs.offcanvas", () => {
                document.body.style.overflow = ""; // Vratite normalno ponašanje tijela
            });
        },
    },
};
</script>

<style></style>
