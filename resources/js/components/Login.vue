<template>
    <v-app>
        <v-content>
            <v-container>Hello world</v-container>
            <Footer></Footer>
        </v-content>
    </v-app>
</template>

<script>
    import footer from './Footer';
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        components:{
            Footer:footer,
        }
    }
</script>
