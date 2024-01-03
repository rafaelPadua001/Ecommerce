
import Dashboard from '@/Pages/Dashboard.vue';
<template>
    <div>
        <Dashboard />
    </div>
    <div>
        <v-container>
            <v-row no-gutters>
                <v-col class="d-flex justify-center flex-column" cols="12" md="8">
                    <v-sheet>
                        <v-card class="mx-auto">
                            <v-card-text>
                                Tabela de cupons aqui
                            </v-card-text>
                        </v-card>
                    </v-sheet>
                </v-col>
            </v-row>
        </v-container>
    </div>
  
</template>

<script>
    import Dashboard from '../Auth/Dashboard.vue';
    export default {
        components: {
            Dashboard
        }
    }
</script>