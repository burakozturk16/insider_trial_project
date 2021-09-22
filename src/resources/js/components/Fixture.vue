<template>
    <div class="page">
        <h2 class="ui header" v-if="!embed">
            Generated Fixtures
            <div class="sub header">
                The Premier League fixtures has been created, you can start the simulation.
            </div>
        </h2>
        <br/>
        <div class="ui four cards basic segment" v-bind:class="{ loading: isLoading }">
            <FixtureCard v-for="(week, index) in weeks" :key="index" :week="index" :matches="week"/>
        </div>
        <br/>
        <router-link :to="`/simulation`" class="ui primary left floated button" v-if="!embed">
            Start Simulation
        </router-link>
    </div>
</template>

<script>

import FixtureCard from "./FixtureCard";

export default {
    name: "Fixture",
    props: ["embed"],
    components: {FixtureCard},
    data(){
        return{
            weeks: [],
            isLoading: true
        }
    },
    created() {
        this.axios
            .get('/api/fixtures')
            .then(response => {
                this.weeks = response.data;
                this.isLoading = false;
            });
    }
}
</script>

<style scoped>
.page{margin-top: 30px}
.floated{margin-left: 1em !important}
</style>
