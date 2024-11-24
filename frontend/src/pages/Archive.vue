<script setup>
import axiosClient from '../axios'
import { onMounted, ref } from 'vue'

const archives = ref({})
const created_at = ref('')
const utcTimeZone = 'cs-CZ'
      
const formatDate = (stringDate) => {
    const date = new Date(stringDate)
    const dateFormat = date.toLocaleDateString(utcTimeZone, { year: 'numeric', month: 'numeric', day: 'numeric' })
    const timeFormat = date.toLocaleTimeString(utcTimeZone, { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false })
    return `${dateFormat} u ${timeFormat}`
}

const itemFormatDate = (stringDate) => {
    const date = new Date(stringDate)
    const day = date.getDate()
    const month = date.getMonth() + 1
    const year = date.getFullYear()
    return `${day}.${month}.${year}.`
}

const getArchives = async () => {
    const {data} = await axiosClient.get('/calendar/get-archive');

    archives.value = data.reduce((groupedBy, item) => {
        (groupedBy[item.order] = groupedBy[item.order] || []).push(item)
        return groupedBy
    }, {});

    for (const order in archives.value) {
        if (archives.value.hasOwnProperty(order)) {
            const group = archives.value[order];
            if (group.length > 0 && !created_at.value) {
                created_at.value = group[0].created_at
            }
        }
    }
}

onMounted(getArchives)
    
</script>

<template>
    <div class="container mb-3">

        <section class="py-3 text-center container">
            <div class="row py-lg-5">
            <div class="col-lg-6 col-md-8 mx-auto">
                <h1 class="fw-light">Registar radnog vremena</h1>
                <p class="lead text-center">Arhiva prijavljenih odsustava</p>
                <p>
                    <router-link :to="{name: 'calendar'}">
                        <button class="btn btn-primary">Natrag na registar</button>
                    </router-link>
                </p>
            </div>
            </div>
        </section>

        <div class="pb-3" v-for="order in Object.keys(archives)" :key="order">
            <h4 class="my-3">{{ order }}. Arhivirano: {{ formatDate(archives[order][0].created_at) }}</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Datum</th>
                        <th scope="col">Tip odsustva</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in archives[order]" :key="index">
                        <td>{{ itemFormatDate(item.date) }}</td>
                        <td>{{ item.absence.name }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
    </div>
</template>
