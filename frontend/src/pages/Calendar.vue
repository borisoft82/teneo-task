<script>
import axiosClient from '../axios';

export default {

    data() {
        return {
            absenceType: '',
            startDate: '',
            endDate: '',
            absenceTypes: [],
            showSuccessAlert: false,
            showArchiveAlert: false,
            absences: [],
            days: [],
            months: ["Januar","Februar","Mart","April","Maj","Juni","Juli","August","Septembar","Octobar","Novembar","Decembar"],
            time: 3000,
        };
    },

    methods: {

        async saveAbsence() {
            this.checkDate()

            const response = await axiosClient.post('/calendar/create-absence', {
                absence: {
                    start_date: this.startDate,
                    end_date: this.endDate,
                    absence_id: this.absenceType,
                },
            });

            if (response.data.success) {
                this.$refs.closeModalButton.click();
                this.showSuccessAlert = true;

                setTimeout(() => {
                    this.showSuccessAlert = false;
                }, this.time);
                
                this.getCalendarAbsences();
                this.clearInputFields();
                
            } else {

                this.showSuccessAlert = false;
                this.$refs.closeModalButton.click();
            }
        },

        async sendToArchive() {
            const response = await axiosClient.get('/calendar/send-to-archive');
            if(response.data.success){
                this.showArchiveAlert = true;
                setTimeout(() => {
                    this.showArchiveAlert = false;
                }, this.time);
            }
        },

        async getCalendarMonth() {
            const {data} = await axiosClient.get('/calendar/month');
            this.days = data;
        },

        async getCalendarAbsenceTypes() {
            const {data} = await axiosClient.get('/calendar/absence-types');
            this.absenceTypes = data;
        },

        async getCalendarAbsences() {
            const {data} = await axiosClient.get('/calendar/absences');
            this.absences = data;
            console.log(this.absences)
        },

        currentMonthAndYear() {
            const month = this.months[new Date().getMonth()]
            const year = new Date().getFullYear()

            return `${month}  ${year}.`
        },

        checkDate() {
            if (new Date(this.startDate) > new Date(this.endDate)) {
                alert('End date must be greater than start date!');
                return;
            }
        },

        clearInputFields() {
            this.absenceType = '';
            this.startDate = '';
            this.endDate = '';
        },
    },

    created() {
        this.getCalendarMonth();
        this.getCalendarAbsenceTypes();
        this.getCalendarAbsences()
    },
};
</script>

<template>
<div>
    <main>
        <section class="py-3 text-center container">
            <div class="row py-lg-5">
            <div class="col-lg-6 col-md-8 mx-auto">
                <h1 class="fw-light">Registar radnog vremena</h1>
                <p class="lead text-center">There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable.</p>
                <p>
                    <button class="btn btn-success me-5" data-bs-toggle="modal" data-bs-target="#registerModal">Registruj
                        odsustvo</button>
                    <button class="btn btn-primary me-2" @click="sendToArchive()">Arhiviraj</button>
                    <router-link :to="{name: 'arhiva'}">
                        <button class="btn btn-primary">Pregled arhive</button>
                    </router-link>
                </p>
            </div>
            </div>
        </section>
    
        <div class="container">
            <h4 class="text-center"> Kalendar za {{ currentMonthAndYear() }}</h4>
            <div class="d-flex justify-content-center align-items-center">

                <div style="height: 80px">
                    <div v-if="showSuccessAlert" class="alert alert-success" role="alert">
                        Odsustvo je uspješno prijavljeno!
                    </div>
                    <div v-if="showArchiveAlert" class="alert alert-success" role="alert">
                        Kalendar je uspješno arhiviran!
                    </div>
                </div>

        </div>

        <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="registerModalLabel">Registruj odsustvo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ref="closeModalButton"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="saveAbsence">
                            <div class="mb-3">
                                <label for="absenceType" class="form-label">Vrsta odsustva</label>
                                <select class="form-select" id="absenceType" v-model="absenceType" required>
                                    <option disabled value="">Izaberi tip</option>
                                    <option v-for="absenceType in absenceTypes" :key="absenceType.id"
                                        :value="absenceType.id">
                                        {{ absenceType . name }}
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="startDate" class="form-label">Početak odsustva</label>
                                <input type="date" class="form-control" id="startDate" v-model="startDate" required>
                            </div>
                            <div class="mb-3">
                                <label for="endDate" class="form-label">Završetak odsustva</label>
                                <input type="date" class="form-control" id="endDate" v-model="endDate" required>
                            </div>
                            <div class="modal-footer d-flex">
                                <button type="submit" class="btn btn-primary">Spremi</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <div class="d-flex flex-wrap">
            <div v-for="(day, index) in days" :key="index" class="p-2 mb-2 me-2"
                style="width: calc(100% / 7 - 0.5rem); position: relative;">
                <div class="card bg-success text-white text-center rounded-0" :class="{ 'bg-body': day.is_weekend }" style="min-height: 205px;">
                    <div class="card-header text-center" :class="{ 'text-dark': day.is_weekend }">
                        {{ new Date(day . date) . getDate() }}
                    </div>
                    <div class="card-body">
                        <div class="card-text">
                            <div class="card-body" :class="{ 'text-dark': day.is_weekend }">
                                <div v-if="absences.some(absence => absence.date === day.date)">
                                    {{ (absences.find(absence => absence.date === day.date) || {}).absence?.name }}
                                </div>
                                <div v-else>Redovan rad</div>
                                <div v-if="absences.some(absence => absence.date === day.date)" class="card-footer" style="font-size: 12px;">
                                    {{ (absences.find(absence => absence.date === day.date) || {}).absence_period }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        
    </main>
</div>
    
</template>
