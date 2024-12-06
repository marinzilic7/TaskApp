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
                    <form @submit.prevent="addGroup()" class="d-flex">
                        <input
                            id="add_list"
                            class="border-0"
                            placeholder="Dodaj novi popis"
                            required
                            v-model="form.title"
                        />
                        <button class="add_list_btn btn btn-primary">
                            <i class="bi bi-check2"></i>
                        </button>
                    </form>
                </div>
                <hr />
                <div>
                    <!-- Grupa -->
                    <div
                        v-if="groups.length > 0"
                        v-for="(group, index) in groups"
                        class="d-flex align-items-center gap-3 mt-3"
                        @contextmenu.prevent="openDropdown($event, index)"
                        @click="closeDropdown(index)"
                    >
                        <i class="bi bi-list fs-4"></i>
                        <RouterLink
                            :to="'/group/' + group.id"
                            class="text-decoration-none text-dark"
                            @click="closeAccordation"
                        >
                            {{ group.title }}
                        </RouterLink>
                        <span class="badge bg-primary ms-auto">{{
                            group.subtasks_count
                        }}</span>
                        <!-- Dropdown meni za brisanje -->
                        <div
                            v-if="group.showDropdown"
                            class="dropdown-menu"
                            style="display: block"
                        >
                            <button
                                class="dropdown-item"
                                @click="deleteGroup(group.id)"
                            >
                                Obriši listu
                            </button>
                        </div>
                    </div>
                    <div v-else>
                        <p class="no-group text-muted text-center">
                            Trenutno nema nijednog dodanog popisa
                        </p>
                    </div>
                    <!-- Ostatak sadržaja -->
                    <hr />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { minutesToHours } from "date-fns";
import { Router } from "react-router-dom";
import { RouterLink } from "vue-router";

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
        group: {
            type: Object,
            required: true,
        },
        subtasks: {
            type: Array,
            required: true,
        },
    },

    data() {
        return {
            form: {
                title: "",
            },
            groups: [],
            activeDropdownIndex: null,
            subtasks: [],
        };
    },
    mounted() {
        this.fixBodyOverflow();
        document.addEventListener("click", this.closeDropdownOnClickOutside); // Dodajemo event listener za klik izvan dropdown menija
    },
    beforeDestroy() {
        document.removeEventListener("click", this.closeDropdownOnClickOutside); // Uklanjamo event listener
    },
    created() {
        this.getGroup();
        this.getGrouptasks();

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
        // -----------------------------------------------------------------------

        addGroup() {
            axios
                .post("/addGroup", this.form)
                .then((response) => {
                    console.log(response);
                    this.getGroup();
                    this.form.title = "";
                    this.getGrouptasks();
                })
                .catch((error) => {
                    if (error.response && error.response.status === 400) {
                        alert(error.response.data.error);
                    } else {
                        alert("Failed to add task.");
                    }
                });
        },
        getGroup() {
            axios
                .get("/getGroup")
                .then((response) => {
                    this.groups = response.data;

                    console.log(response);
                })
                .catch((error) => {
                    if (error.response && error.response.status === 400) {
                        alert(error.response.data.error);
                    } else {
                        alert("Failed to get task.");
                    }
                });
        },
        openDropdown(event, index) {
            this.groups.forEach((group, i) => {
                group.showDropdown = i === index; // Prikazuje dropdown samo za kliknutu grupu
            });
            this.activeDropdownIndex = index;
        },
        closeDropdownOnClickOutside(event) {
            // Zatvori dropdown ako korisnik klikne izvan dropdown menija
            if (
                this.activeDropdownIndex !== null &&
                !event.target.closest(".dropdown-menu") &&
                !event.target.closest(".group-item")
            ) {
                this.groups[this.activeDropdownIndex].showDropdown = false;
                this.activeDropdownIndex = null;
            }
        },

        closeDropdown(index) {
            // Zatvara dropdown kada se klikne na istu grupu
            if (this.activeDropdownIndex === index) {
                this.groups[index].showDropdown = false;
                this.activeDropdownIndex = null; // Postavi aktivni dropdown na null
            }
        },

        // Funkcija za brisanje grupe
        deleteGroup(groupId) {
            axios
                .post(`/deleteGroup/${groupId}`)
                .then(() => {
                    this.groups = this.groups.filter(
                        (group) => group.id !== groupId
                    );
                    this.getGrouptasks(); // Ukloni obrisanu grupu iz popisa
                })
                .catch((error) => {
                    alert("Failed to delete group.");
                });
        },

        getGrouptasks() {
            axios
                .get("/getGroupsWithTaskCount")
                .then((response) => {
                    this.groups = response.data;
                    console.log("OVO JE BROJ", this.countSubtasks);
                })
                .catch((error) => {
                    console.error("Failed to fetch groups:", error);
                    alert("Došlo je do pogreške prilikom dohvaćanja grupa.");
                });
        },


    },
};
</script>

<style>
#add_list {
    outline: none !important;
}

#add_list:focus {
    border: none;
    outline: none;
}

#add_list:hover {
    border: none;
}

.dropdown-menu {
    position: absolute;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 8px 0;
    border-radius: 4px;
    z-index: 999;
    right: 0%;
    margin-right: 30px;
}

.list-items {
    cursor: pointer;
}

.list-items:hover {
    background-color: #f8f9fa;
}

.no-group {
    font-size: 13px;
}
</style>
