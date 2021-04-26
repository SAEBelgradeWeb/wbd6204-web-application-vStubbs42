<template>
    <CCardBody>
        <div>
                <label for="startDate">Start date</label>
                <input type="date" v-model="ddate1" v-on:change="getTotalPrice()" id="startDate" name="startDate" />
                <label for="endDate">End date</label>
                <input type="date" v-model="ddate2" v-on:change="getTotalPrice()" id="endDate" name="endDate" />
                <label for="campaignName">Campaign name</label>
                <input type="text" v-model="campaignName" id="campaignName" name="campaignName" />
                Total price: {{ this.totalPrice}}
            <CButton size="sm" color="danger" class="ml-1">
                Confirm
            </CButton>
        </div>
        <CDataTable
            :items="items"
            :fields="fields"
            columnFilter
            :items-per-page="500"
            hover
            sorter
            pagination
        >
        >
            <template #status="{item}">
                <td>
                    <CBadge :color="getBadge(item.status)">
                        {{item.status}}
                    </CBadge>
                </td>
            </template>
            <template #show_details="{item, index} ">
                <td class="py-auto">
                    <CButton
                        color="primary"
                        variant="outline"
                        square
                        size="sm"
                        @click="toggleSelection(item, index)"
                    >
                        {{Boolean(item._selected) ? 'Remove' : 'Select'}}
                    </CButton>
                </td>
                </template>
            <template #show_selection="{item, index} ">
                <td class="py-auto">
                    <CButton
                        color="secondary"
                        variant="outline"
                        square
                        size="sm"
                        @click="toggleDetails(item, index)"
                    >
                        {{Boolean(item._toggled) ? 'Hide' : 'Show'}}
                    </CButton>
                </td>
            </template>
            <template #details="{item}">
                <CCollapse :show="Boolean(item._toggled)" :duration="collapseDuration">
                    <CCardBody>
                        <CMedia :aside-image-props="{ height: 102 }">
                            <h4>
                                ID:{{item.id}}
                            </h4>
                            <p class="text-muted">Weekly price: {{item.price}}</p>
                            <CButton size="sm" color="info" class="">
                                User Settings
                            </CButton>
                            <CButton size="sm" color="danger" class="ml-1">
                                Delete
                            </CButton>
                        </CMedia>
                    </CCardBody>
                </CCollapse>
            </template>
        </CDataTable>
    </CCardBody>
</template>

<script>
const items = [
]

const fields = [
    { key: 'type', _style:'min-width:200px'},
    { key: 'size', _style:'min-width:200px'},
    { key: 'location', _style:'min-width:100px;' },
    { key: 'price', _style:'min-width:100px;' },
    { key: 'status', _style:' min-width:100px;' },
    {
        key: 'show_details',
        label: '',
        _style: 'width:1%',
        sorter: false,
        filter: false
    },
    {
        key: 'show_selection',
        label: '',
        _style: 'width:1%;',
        sorter: false,
        filter: false
    },
]

export default {
    name: 'AdvancedTables',
    data () {
        return {
            ddate1:[],
            ddate2:[],
            date1:[],
            date2:[],
            dDiff:0,
            items: [],
            boardSelection: [],
            campaignName:0,
            singlePrice: 0,
            totalPrice:0,
            fields,
            details: [],
            collapseDuration: 0
        }
    },
    async mounted() {
        this.date1 = new Date();
        this.date2 = new Date(Date.now() + 604800000);
        this.ddate1 = this.date1.toISOString().slice(0, 10);
        this.ddate2 = this.date2.toISOString().slice(0, 10);
        this.dateDifference();
        const result = await axios.get('/api/boards');
        const items = result.data;
        this.items = items.map((item, id) => { return {...item, id}});
        this.statusHandler(this.items);
    },
    methods: {
        getBadge (status) {
            switch (status) {
                case 'Selected': return 'success'
                case 'Pending': return 'secondary'
                case 'Available': return 'warning'
                case 'Unavailable': return 'danger'
                default: 'primary'
            }
        },
        statusHandler (items) {
            items.forEach(element => element.status = "Available");
        },
        confirm () {
            axios.post('http://localhost:3030/api/new/post',
                this.boardSelection, // the data to post
                this.campaignName,
                this.ddate1,
                this.ddate2,

                { headers: {
                        'Content-type': 'application/x-www-form-urlencoded',
                    }
                }).then(response => console.log('suma'));
        },
        dateDifference () {
            this.dDiff = Math.ceil((Date.parse(this.ddate2)- Date.parse(this.ddate1))/ 604800000);
        },
        getTotalPrice () {
            this.dateDifference();
            this.totalPrice = this.singlePrice * this.dDiff;
            console.log(this.singlePrice);
            console.log(this.dDiff);
            console.log(this.totalPrice);
        },
        toggleDetails (item) {
            this.$set(this.items[item.id], '_toggled', !item._toggled)
            this.collapseDuration = 300
            this.$nextTick(() => { this.collapseDuration = 0})
        },
        toggleSelection (item) {
            if(item.status === 'Available'){
                this.boardSelection.push(item.id);
                this.singlePrice += item.price;
                this.$set(this.items[item.id], '_selected', !item._selected);
                item.status = "Selected";
            }
            else if(item.status === "Selected"){
                let spec = this.boardSelection.indexOf(item.id)
                this.singlePrice -= item.price;
                this.boardSelection.splice(spec, 1);
                this.$set(this.items[item.id], '_selected', !item._selected);
                item.status = "Available";
            }
            this.getTotalPrice ()
            // this.collapseDuration = 300
            // this.$nextTick(() => { this.collapseDuration = 0})
        }
    }
}
</script>
