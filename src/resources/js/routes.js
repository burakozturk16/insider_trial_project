import Welcome from './components/Welcome.vue';
import Teams from './components/Teams.vue';
import Fixture from './components/Fixture.vue';
import Simulation from './components/Simulation.vue';


export const routes = [
    {
        name: 'home',
        path: '/',
        component: Welcome
    },
    {
        name: 'select_teams',
        path: '/teams/:type',
        component: Teams
    },
    {
        name: 'fixture',
        path: '/fixture',
        component: Fixture
    },
    {
        name: 'simulation',
        path: '/simulation',
        component: Simulation
    }
];
