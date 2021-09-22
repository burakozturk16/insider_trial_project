<template>
    <div class="page">
        <h2 class="ui header">
            EPL Simulation
            <div class="sub header">
                You can simulate all weeks or play week by week. Also you can edit score!
            </div>
        </h2>
        <br/>
        <div class="ui grid basic segment" v-bind:class="{loading: isLoading}">
            <div class="eight wide column">
                <table class="ui very celled table">
                    <thead>
                    <tr>
                        <th></th>
                        <th class="tdCenter">P</th>
                        <th class="tdCenter">W</th>
                        <th class="tdCenter">D</th>
                        <th class="tdCenter">L</th>
                        <th class="tdCenter">GD</th>
                        <th class="tdCenter bgPts">PTS</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="row in detail.leader_board">
                        <td>
                            <h4 class="ui image header">
                                <img :src="row.logo" class="ui mini rounded image">
                                <div class="content">
                                    {{ row.title }}
                                </div>
                            </h4>
                        </td>
                        <td class="tdCenter">{{ row.played ? row.played : '0' }}</td>
                        <td class="tdCenter">{{ row.wins }}</td>
                        <td class="tdCenter">{{ row.draws }}</td>
                        <td class="tdCenter">{{ row.loses ? row.loses : '0'}}</td>
                        <td class="tdCenter">{{ row.gd ? row.gd : '0' }}</td>
                        <td class="tdCenter bgPts">{{ row.pts }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="four wide column">
                <FixtureCard :week="this.currentWeek" :matches="detail.week_results" />
            </div>
            <div class="four wide column">
                <div class="ui card">
                    <div class="content cardBg">
                        <div class="header">Championship Predications</div>
                    </div>
                    <div class="content">
                        <table class="ui" width="100%">
                            <tr v-for="(pre, index) in detail.predications" :key="index">
                                <td class="tdLeft">{{ detail.leader_board[index].title }}</td>
                                <td class="tdRight">{{ pre.toFixed(1) }} %</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <br/>
        <div class="ui grid">
            <div class="four wide column left aligned" style="padding-left: 2em">
                <button class="ui primary button" @click="playAll" v-bind:class="{disabled: disabled}">Play All</button>
            </div>
            <div class="eight wide column">
                <button class="ui primary button" @click="play" v-bind:class="{disabled: disabled}">Play This Week</button>
                <button class="ui primary button" @click="playNext" v-bind:class="{disabled: disabled}">Play Next Week</button>
            </div>
            <div class="four wide column right aligned" style="padding-right: 2em">
                <button class="ui red button" @click="reset">Reset</button>
                <button class="ui red button" @click="resetAll">Reset with Teams</button>
            </div>
        </div>
        <Fixture v-if="showAll" embed="true"/>
    </div>
</template>

<script>
import Fixture from "./Fixture";
import FixtureCard from "./FixtureCard"

export default {
    name: "Simulation",
    components: {FixtureCard, Fixture},
    data(){
      return {
          detail: {},
          currentWeek: 1,
          tournament: 0,
          played: 0,
          isLoading: true,
          disabled: false,
          showAll: false
      }
    },
    created(){
        const storedWeek = localStorage.getItem('week');
        const storedPlayed = localStorage.getItem('played');

        this.tournament = localStorage.getItem('tournament');

        if(!storedPlayed){
            localStorage.setItem('played', this.played);
        }else{
            this.played = parseInt(storedPlayed);
        }

        if(!storedWeek){
            localStorage.setItem('week', this.currentWeek);
        }else{
            this.currentWeek = parseInt(storedWeek);
        }

        this.refresh();
    },
    methods: {
        playNext(){
            if(this.played > 0){
                this.updateWeek();
            }

            this.play();
        },
        play(){
            this.axios
                .get('/api/simulation/play/' + this.currentWeek)
                .then(() => {
                    this.setPlayed(this.currentWeek);
                    this.refresh();
                })

        },
        playAll(){
            this.axios
                .get('/api/simulation/play/all/' + this.tournament + "/" + this.currentWeek)
                .then(() => {
                    this.currentWeek = this.tournament;
                    this.showAll = true;
                    this.refresh();
                })
        },
        setPlayed(n){
            this.played = n;
            localStorage.setItem('played', n);
        },
        reset(){
            this.resetWeek();
            this.setPlayed(0);
            this.showAll = false;

            this.axios.get('/api/simulation/reset/tournament').then(() => {
                this.refresh()
            })
        },
        resetAll(){
            localStorage.removeItem('tournament');
            localStorage.removeItem('played');
            this.showAll = false;

            this.resetWeek();

            this.axios.get('/api/simulation/reset/all').then(() => {
                this.$router.push({ name: 'home'});
            })
        },
        resetWeek(){
            this.currentWeek = 1;
            localStorage.removeItem('week');
        },
        updateWeek(){
            this.currentWeek++;

            localStorage.setItem('week', this.currentWeek);
        },
        refresh(){
            this.isLoading = true;

            this.axios
                .get('/api/simulation/' + this.currentWeek)
                .then(response => {
                    this.detail = response.data;
                    this.isLoading = false;

                    if(this.currentWeek >= this.tournament){
                        this.disabled = true;
                    }else{
                        this.disabled = false;
                    }
                })
        }
    }
}
</script>

<style scoped>
.page{margin-top: 30px}
.tdCenter {text-align: center !important}
.tdLeft {text-align: left !important}
.tdRight {text-align: right !important}
.bgPts{background: #F9FAFB !important; font-weight: bold !important}
</style>
