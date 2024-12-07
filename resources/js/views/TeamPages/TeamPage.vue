<script setup>
import Navbar from "@/components/Navbar.vue";
</script>

<template>
    <div>
        <Navbar />
    </div>
    <div>

    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            teamId: this.$route.params.id, // Dohvaćanje ID-a iz URL-a
            team: {}, // Objekt za pohranu podataka tima
        };
    },
    created() {
        // Poziv metode za dohvat tima nakon što je komponenta kreirana
        this.getTeamData(this.teamId);
    },
    methods: {
        async getTeamData(id) {
            try {
                const response = await axios.get(`/getTeamData/${id}`); // API endpoint za dohvat podataka o grupi
                this.team = response.data; // Pohranjivanje podataka u 'team' objekt
                console.log("Ovo su podaci grupe", this.team); // Ispis podataka u konzoli
            } catch (error) {
                console.error("Error fetching group data:", error); // Obrada pogreške
            }
        },
    },
};
</script>

<style></style>
