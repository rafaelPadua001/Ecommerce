<template>
    <v-container>
        <v-sheet>
            <v-row justify="center">
                <v-col cols="auto">
                    <v-row>
                        <v-col>
                            <p class="text-h4"><strong>{{ responsePayment.data.original['Payment']['ReturnMessage'] }}</strong></p>
                        </v-col>
                    </v-row>
                    <v-divider></v-divider>
                    <v-spacer></v-spacer>

                    <v-row justify="start">
                        <v-col>
                            <p class="text-h6 float-start">Payer:</p>
                        </v-col>
                    </v-row>
                 
                    <v-spacer></v-spacer>
                    <v-row>
                        <v-col cols="auto">
                            <p class="float-start">Payer: <strong>{{ responsePayment.data.original['Customer']['Name'] }}</strong></p>
                           
                        </v-col>
                        <v-col>

                            <p class="float-start">Payer Email: <strong>{{ responsePayment.data.original['Customer']['Email'] }}</strong></p>
                            
                        </v-col>
                    </v-row>
                   
                    <v-spacer></v-spacer>

                    <v-row>
                        <v-col>
                            <v-row justify="start">
                                <p class="text-h6">Order:</p>
                                
                            </v-row>
                            
                        </v-col>
                    </v-row>
                    

                    <v-row>
                        <v-col>
                            <p class="float-start"><strong>MerchantOrderId:</strong> {{ responsePayment.data.original['MerchantOrderId'] }}</p>
                        </v-col>
                    </v-row>
                    <v-spacer></v-spacer>

                    <v-row>
                        <v-col>
                            <v-row justify="start">
                                <p class="text-h6">Payment</p>
                            </v-row>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col>
                            <p class="float-start"><strong>Pament type:</strong> {{ responsePayment.data.original['Payment']['Type'] }} </p>
                        </v-col>
                        <v-col>
                            <p class="float-start"><strong>Card Number:</strong> {{ responsePayment.data.original['Payment']['DebitCard']['CardNumber'] }} </p>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <p class="float-start"><strong>Holder:</strong> {{ responsePayment.data.original['Payment']['DebitCard']['Holder'] }} </p>
                        </v-col>
                        <v-col>
                            <p class="float-start"><strong>Brand:</strong> {{ responsePayment.data.original['Payment']['DebitCard']['Brand'] }} </p>  
                        </v-col>
                    </v-row>
                   <v-row>
                    <v-col>
                        <p class="float-start">Currency: {{responsePayment.data.original['Payment']['Currency']}}</p>
                    </v-col>
                   </v-row>

                   <v-row>
                        <v-col>
                            <v-btn variant="text" :to="'/'">Home</v-btn>

                        </v-col>
                   </v-row>
                    <!-- {{responsePayment.data.original}}
   
                    <v-spacer></v-spacer>
    {{ 'Status:' + responsePayment.status }} -->
                </v-col>
            </v-row>
        </v-sheet>
    </v-container>
  
</template>

<script>
    export default {
        name: 'Completed',
        props: ['responsePayment']
    }
</script>