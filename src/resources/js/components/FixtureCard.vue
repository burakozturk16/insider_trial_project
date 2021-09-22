<template>
    <div class="ui card">
        <div class="content cardBg">
            <div class="header">Week {{ week }}</div>
        </div>
        <div class="content">
            <table class="ui center aliged" width="100%">
                <tr v-for="(match, index) in matches" :key="index">
                    <td>{{ match.home.title }}</td>
                    <td><a href="#" @click.prevent="edit('home', index)">{{ match.home_score }}</a> - <a href="#" @click.prevent="edit('away', index)">{{ match.away_score }}</a></td>
                    <td>{{ match.away.title }}</td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    name: "FixtureCard",
    props: ["week", "matches"],
    created(){

    },
    methods:{
        edit(side, index){
            const score = window.prompt("Please type new score:");
            const row_id = this.$props.matches[index].row_id;

            this.axios.post('/api/score/edit', {row_id, side, score})
                      .then(response => {
                          this.$parent.refresh();
                      })

        }
    }
}
</script>

<style scoped>
.cardBg{ background: #cce2ff !important}
</style>
