<template>
    <div class="page">
        <h2 class="ui header">
            EPL Championship Simulation
            <div class="sub header">
                There are two mode to playing simulation, please select one.
            </div>
        </h2>
        <div class="ui placeholder segment">
            <div class="ui two column stackable center aligned grid">
                <div class="ui vertical divider">Or</div>
                <div class="middle aligned row">
                    <div class="column">
                        <div class="ui icon header">
                            <i class="trophy icon"></i>
                            Machine Learning
                        </div>
                        <router-link to="/teams/ml" class="ui primary button disabled">
                            Select
                        </router-link>
                    </div>
                    <div class="column">
                        <div class="ui icon header">
                            <i class="futbol icon"></i>
                            My Algorithm
                        </div>
                        <router-link to="/teams/normal" class="ui primary button">
                            Select
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {

}
</script>

<style scoped>
.page{margin-top: 80px;}
.segment{margin-top: 30px !important}
</style>
