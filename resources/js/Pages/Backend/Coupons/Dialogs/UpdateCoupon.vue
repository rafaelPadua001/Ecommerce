<template>
    <v-row fluid>
        <v-col cols="12" md="12">
            <v-card class="mx-auto" width="500">
                <template v-slot:append>
                       
                    <v-btn variant="plain" icon>
                        <v-icon icon="fas fa-close"></v-icon>
                    </v-btn>
                </template>
                <v-row fluid>
                    <v-col>
                        <v-text-field v-model="editCoupon.code" label="Código do Cupom" required
                            :rules="codeRules"></v-text-field>
                    </v-col>
                </v-row>

                <v-row fluid>
                    <v-col>
                        <v-text-field v-model="editCoupon.discount_percentage" label="Desconto (%)" required
                            :rules="valueRules"></v-text-field>

                    </v-col>
                </v-row>

                <v-row>
                    <v-col>
                        <v-card-actions>
                            <v-btn-group>
                                <v-btn>Save</v-btn>
                                <v-btn>Close</v-btn>
                            </v-btn-group>
                        </v-card-actions>
                    </v-col>
                </v-row>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
export default {
    props: ['editCoupon'],
    data: () => ({

    }),
    methods: {

    }
}
</script>