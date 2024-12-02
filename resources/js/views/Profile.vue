<script setup>
import Navbar from "../components/Navbar.vue";
import { ref } from 'vue';
import axios from 'axios';

const image = ref(null);

const uploadImage = async () => {
    const formData = new FormData();
    formData.append('image', image.value);

    try {
        const response = await axios.post('/uploadProfile', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        });
        alert(response.data.message);  // Ako je upload uspješan
    } catch (error) {
        if (error.response && error.response.status === 400) {
            alert(error.response.data.error);  // Prikaz poruke o grešci iz backend-a
        } else {
            alert('Failed to upload image.');
        }

    }
};

const imageChange = (event) => {
    image.value = event.target.files[0];
};
</script>

<template>
    <div>
        <Navbar />
    </div>
    <div>
        <h1>Profile page</h1>
        <form @submit.prevent="uploadImage" enctype="multipart/form-data">
            <input type="file" @change="imageChange" />
            <button type="submit">Upload Image</button>
        </form>
    </div>
</template>

<style></style>
