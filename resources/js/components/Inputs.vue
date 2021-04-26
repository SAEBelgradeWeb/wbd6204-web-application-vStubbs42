<template>
    <div>
        <CRow>
            <CCol sm="4">
                <CSelect
                    label="Month"
                    :options="[1,2,3,4,5,6,7,8,9,10,11,12]"
                />
            </CCol>
            <CCol sm="4">
                <CSelect
                    label="Year"
                    :options="[2014,2015,2016,2017,2018,2019,2020,2021,2022,2023,2024,2025]"
                />
            </CCol>
        </CRow>
    </div>
</template>

<script>

export default {
    name: 'AdvancedTables',
    data () {
        return {
            test: "test",
        }
    },
    mounted () {
        this.datatest ();
    },
    methods: {
        datatest () {
            console.log(this.boards);
        },
    }
}
</script>
