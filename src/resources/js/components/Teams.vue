<template>
    <div class="page">
        <h2 class="ui header">
            Tournament Teams
            <div class="sub header">
                Select the teams as even that you want to include tournament.
            </div>
        </h2>
        <div class="ui segment" v-bind:class="{ loading: isLoading }">
            <table class="ui very basic  celled striped table">
                <thead>
                    <tr>
                        <th>Team</th>
                        <th class="tdCenter">Defence</th>
                        <th class="tdCenter">Middle</th>
                        <th class="tdCenter">Attack</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="team in teams" :key="team.id">
                        <td>
                            <h4 class="ui image header">
                                <input type="checkbox" :value="team.team_id" @change="select($event)"/>
                                <img :src="team.logo" class="ui mini rounded image" style="margin-left: 5px">
                                <div class="content">
                                    {{ team.title }}
                                    <div class="sub header"><small>Overall {{ ((parseInt(team.def) + parseInt(team.mid) + parseInt(team.att)) / 3).toFixed(0) }}</small></div>
                                </div>
                            </h4></td>
                        <td class="tdCenter">{{ team.def }}</td>
                        <td class="tdCenter">{{ team.mid }}</td>
                        <td class="tdCenter">{{ team.att }}</td>
                    </tr>
                </tbody>
            </table>
            <br/>
            <button class="ui button" v-bind:class="{ disabled: !canGenerate || isCreating, loading: isCreating }" @click="generateFixtures">Generate Fixtures</button>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            teams: [],
            isLoading: true,
            canGenerate: false,
            isCreating: false
        }
    },
    created() {
        this.axios
            .get('/api/teams')
            .then(response => {
                this.teams = response.data;
                this.isLoading = false;

                localStorage.setItem('mode', this.$route.params.type);
                localStorage.setItem('week', 1);
            });
    },
    methods: {
        select(e){
            const  id = e.target.value;
            const teamIndex = this.teams.findIndex(team => team.team_id === parseInt(id));
            const isSelected = this.teams[teamIndex]['selected'];

            if(!isSelected){
                this.teams[teamIndex]['selected'] = true;
            }else{
                this.teams[teamIndex]['selected'] = false;
            }

            const selectedCount = this.teams.reduce((val, team) => team.selected ? val + 1 : val, 0)

            this.canGenerate = selectedCount % 2 === 0;
        },
        generateFixtures(){
            this.isCreating = true;

            const selectedTeams = this.teams.filter(t => t.selected).map(t => t.team_id);

            this.axios
                .post('/api/fixtures', {selectedTeams})
                .then((response) => {
                    localStorage.setItem('tournament', response.data);
                    this.$router.push({ name: 'fixture'});
                })
        }
    }
}
</script>

<style scoped>
.page{margin-top: 80px; margin-bottom: 50px}
.tdCenter {text-align: center !important}
</style>
