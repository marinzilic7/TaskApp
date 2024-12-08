<script setup>
import Navbar from "../components/Navbar.vue";
</script>

<template>
    <div>
        <Navbar />
    </div>
    <h3 class="text-center mt-5">Tvoji timovi</h3>
    <div
        class="accordion accordion-flush border mt-5 ms-5 me-5"
        id="accordionFlushExample"
    >
        <div class="accordion-item" v-for="team in yourTeam" :key="team.id">
            <h2 class="accordion-header">
                <button
                    class="accordion-button collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#flush-collapseOne"
                    aria-expanded="false"
                    aria-controls="flush-collapseOne"
                    @click="getProject(team.team.id)"
                >
                    {{ team.team.name }}
                </button>
            </h2>
            <div
                id="flush-collapseOne"
                class="accordion-collapse collapse"
                data-bs-parent="#accordionFlushExample"
            >
                <div class="accordion-body">
                    <ul>
                        <li>
                            <h5>Projekti</h5>
                            <ul>
                                <li v-for="project in projects" :key="project.id">
                                    {{ project.name }}

                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            yourTeam: [],
            projects: [],
        };
    },
    created() {
        this.getYourTeam();
    },
    methods: {
        getYourTeam() {
            axios
                .get("/getYourTeam")
                .then((response) => {
                    this.yourTeam = response.data;
                })
                .catch((error) => {
                    console.error("Error getting your team:", error);
                });
        },
        getProject(teamId) {
            axios
                .get(`/getProject/${teamId}`)
                .then((response) => {
                    this.projects = response.data;

                    // Iteracija kroz projekte da dohvatimo zadatke
                    this.projects = this.projects.map((project) => {
                        axios
                            .get(`/getTasksByProject/${project.id}`)
                            .then((taskResponse) => {
                                // Dodavanje tasks polja projektu
                                project.tasks = taskResponse.data;
                            })
                            .catch((error) => {
                                console.error(
                                    "Error getting tasks for project:",
                                    error
                                );
                            });
                        return project;
                    });

                    console.log("Ovo su projekti s taskovima", this.projects);
                })
                .catch((error) => {
                    console.error("Error getting projects:", error);
                });
        },
    },
};
</script>

<style></style>
