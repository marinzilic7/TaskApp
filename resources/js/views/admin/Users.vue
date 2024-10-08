<template>
    <div>
        <h2 class="text-center mt-5">Pregled svih korisnika</h2>
    </div>
    <div class="table-responsive mt-5 shadow-lg p-5">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Ime</th>
                    <th scope="col">Prezime</th>
                    <th scope="col">Email</th>
                    <th scope="col">Uloga</th>
                    <th scope="col">Datum reg.</th>
                    <th scope="col">Online</th>
                    <th scope="col">Akcije</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users" :key="user.id">
                    <th scope="row">{{ user.id }}</th>
                    <td>{{ user.firstName }}</td>
                    <td>{{ user.lastName }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.role }}</td>
                    <td>{{ formatDate(user.created_at) }}</td>
                    <td class="time" v-if="!user.is_online">
                        {{ formatTime(user.last_online) }}
                    </td>
                    <td v-else>
                        <span v-if="user.is_online" class="badge bg-success"
                            >Aktivan</span
                        >
                        <span v-else class="badge bg-secondary">Neaktivan</span>
                    </td>
                    <td class="d-flex gap-2">
                        <button
                            v-if="user.role !== 'admin'"
                            @click="openModal(user.id)"
                            class="bg-transparent border-0"
                            title="Promoviraj korisnika"
                        >
                            <i
                                id="update-icon"
                                class="bi bi-person-fill-add text-primary fs-4"
                            ></i>
                        </button>
                        <button
                            v-if="user.role === 'admin'"
                            :disabled="true"
                            class="bg-transparent border-0"
                            title="Administrator"
                        >
                            <i
                                class="bi bi-person-fill-lock text-success fs-4"
                            ></i>
                        </button>
                        <button
                            @click="showDeleteModal(user)"
                            v-else
                            class="bg-transparent border-0"
                            title="Ukloni korisnika"
                        >
                            <i
                                id="delete-icon"
                                class="bi bi-person-dash text-danger fs-4"
                            ></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Modal for Confirmation -->
    <div
        v-if="isModalVisible"
        class="modal fade show"
        style="display: block"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Potvrdi promociju
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        @click="closeModal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    Jeste li sigurni da želite promovirati ovog korisnika u
                    administratora?
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="closeModal"
                    >
                        Zatvori
                    </button>
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="promoteToAdmin"
                    >
                        Potvrdi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div
        v-if="isDeleteModalVisible"
        class="modal fade show"
        style="display: block"
        tabindex="-1"
        aria-labelledby="deleteModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">
                        Potvrda brisanja
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        @click="closeDeleteModal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">
                    <p>
                        Jeste li sigurni da želite obrisati korisnika:
                        {{ selectedUser?.firstName }}
                        {{ selectedUser?.lastName }}?
                    </p>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="closeDeleteModal"
                    >
                        Zatvori
                    </button>
                    <button
                        type="button"
                        class="btn btn-danger"
                        @click="confirmDeleteUser"
                    >
                        Obriši
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { format, formatDistanceToNow, parseISO } from "date-fns"; // Importanje date-fns za formatiranje datuma
import { hr } from "date-fns/locale"; // Hrvatski lokalitet za ispravan prikaz mjeseca i dana

export default {
    name: "Users",
    data() {
        return {
            users: [],
            isModalVisible: false, // Stavka za kontrolu prikaza modala
            selectedUserId: null, // ID korisnika koji se promovira
            isDeleteModalVisible: false, // Kontrola vidljivosti modala
            selectedUser: null,
        };
    },
    created() {
        this.fetchUsers();
    },
    methods: {
        fetchUsers() {
            axios
                .get("/getUsers")
                .then((response) => {
                    this.users = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        formatDate(date) {
            return format(new Date(date), "d. MMMM yyyy.", { locale: hr });
        },
        formatTime(date) {
            if (!date) {
                return "Nikad";
            }
            const localTime = new Date(date);
            const timeDifference = formatDistanceToNow(localTime, {
                addSuffix: true,
                locale: hr,
            });
            return timeDifference;
        },
        deleteUser(id) {
            axios
                .post(`/deleteUser/${id}`)
                .then((response) => {
                    this.fetchUsers();
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        // Otvori modal za promociju korisnika
        openModal(userId) {
            this.selectedUserId = userId;
            this.isModalVisible = true;
        },
        // Zatvori modal
        closeModal() {
            this.isModalVisible = false;
            this.selectedUserId = null;
        },
        // Promovira korisnika u admina
        promoteToAdmin() {
            axios
                .post(`/promoteUser/${this.selectedUserId}`)
                .then((response) => {
                    this.fetchUsers(); // Ponovno učitavanje korisnika nakon promocije
                    this.closeModal(); // Zatvaranje modala
                    alert("Korisnik je uspješno promoviran u administratora.");
                })
                .catch((error) => {
                    console.log(error);
                    alert("Došlo je do greške prilikom promocije.");
                });
        },
        showDeleteModal(user) {
            this.selectedUser = user; // Spremi korisnika kojeg brišeš
            this.isDeleteModalVisible = true; // Prikaži modal
        },
        closeDeleteModal() {
            this.isDeleteModalVisible = false; // Zatvori modal
            this.selectedUser = null; // Resetiraj korisnika
        },
        confirmDeleteUser() {
            if (this.selectedUser) {
                axios
                    .post(`/deleteUser/${this.selectedUser.id}`) // Pošalje zahtjev za brisanje korisnika
                    .then((response) => {
                        this.fetchUsers(); // Ponovno dohvaća sve korisnike nakon brisanja
                        this.closeDeleteModal(); // Zatvara modal
                    })
                    .catch((error) => {
                        console.log(error); // Logira greške
                        this.closeDeleteModal(); // Zatvori modal u slučaju greške
                    });
            }
        },
    },
};
</script>

<style scoped>
#update-icon {
    cursor: pointer;
}

#delete-icon {
    cursor: pointer;
}
.time {
    font-size: 0.9rem;
}
</style>
