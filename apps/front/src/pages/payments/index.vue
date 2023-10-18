<template>
  <UAlert
    v-if="showSuccessAlert"
    color="green"
    variant="solid"
    :title="$t('pages.payment.index.successAlertTitle')"
    :description="$t('pages.payment.index.successAlertDescription')"
  />

  <table v-if="payments" class="custom-table">
    <thead>
      <tr>
        <th>{{ $t('pages.payment.index.table_date') }}</th>
        <th>{{ $t('pages.payment.index.table_montant') }}</th>
        <th>{{ $t('pages.payment.index.table_label') }}</th>
        <th>{{ $t('pages.payment.index.table_location') }}</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="payment in payments" :key="payment.id">
        <td>{{ payment.created_at }}</td>
        <td>{{ payment.amount }} €</td>
        <td>{{ payment.label }}</td>
        <td v-if="payment.location == null">
          <UButton :label="$t('pages.payment.index.locatebutton')" @click="openModal(payment)" />
        </td>
        <td v-else>
          {{ payment.location }}
        </td>
      </tr>
    </tbody>
  </table>

  <UModal v-model="isOpen">
    <UCard :ui="{ divide: 'divide-y divide-gray-100 dark:divide-gray-800' }">
      <template #header>{{ $t('pages.payment.index.choosePlaceHeader') }} </template>
      <div v-if="searching">{{ $t('pages.payment.index.searchingPlaces') }}</div>
      <div v-else-if="places.length > 0" class="places-container">
        <div
          v-for="place in places"
          :key="place.place_id"
          class="place-item"
          @click="selectPlace(place)"
        >
          {{ place.name }} - {{ place.vicinity }}
        </div>
      </div>
      <div v-else>{{ $t('pages.payment.index.noPlacesFound') }}</div>

      <template #footer>
        <UButton
          :label="$t('pages.payment.index.closeModalButton')"
          color="gray"
          variant="solid"
          @click="isOpen = false"
        />
      </template>
    </UCard>
  </UModal>
</template>


<script setup lang="ts">
import { FormattedPayment } from "~/types/FormattedPayment";
import useAuthUser from "~/store/auth";
import useListPayments from "~/composables/api/payment/useListPayments";
import { API_URL } from "~/constants/http";

const isOpen = ref(false);
const places = ref<any[]>([]);
const currentRow = ref<FormattedPayment | null>(null);
const showSuccessAlert = ref(false);
const nextPageToken = ref<string | null>(null);
const searching = ref(false);

const {
  data: payments,
  error,
  pending: paymentsPending,
  refresh: paymentsRefresh,
} = await useListPayments();

const formattedPayments = ref<FormattedPayment[]>([]);

const fetchPayments = async () => {
  const paymentsResponse = await useListPayments();
  formattedPayments.value = paymentsResponse.data.value.map((payment) => {
    return {
      ...payment,
      amount: `${payment.amount} €`,
      location: payment.location || "NO_LOCATION",
    };
  });
};

interface Photo {
  height: number;
  html_attributions: string[];
  photo_reference: string;
  width: number;
}

interface Location {
  lat: number;
  lng: number;
}

interface Geometry {
  location: Location;
}

interface Place {
  geometry: Geometry;
  icon: string;
  name: string;
  photos: Photo[];
  place_id: string;
  reference: string;
  types: string[];
  vicinity: string;
}

interface NearbyPlacesResponse {
  results: {
    html_attributions: any[];
    next_page_token?: string;
    results: Place[];
  };
}

async function openModal(row: FormattedPayment) {
  currentRow.value = row;
  isOpen.value = true;
  searching.value = true; // indicate that we're searching now

  nextPageToken.value = null; // reset the token

  const [lat, lng] = row.gps_position
    .split(",")
    .map((coord) => parseFloat(coord));

  try {
    const nearbyPlacesData = await getNearbyPlaces(lat, lng);
    places.value = nearbyPlacesData.results.results;
    nextPageToken.value = nearbyPlacesData.results.next_page_token || null;
  } catch (error) {
    console.error("Error fetching places:", error);
  } finally {
    searching.value = false; // reset the searching state
  }
}


async function getNearbyPlaces(
  lat: number,
  lng: number,
  pagetoken: string | null = null
): Promise<NearbyPlacesResponse> {
  let endpoint = API_URL + `/nearby-places?lat=${lat}&lng=${lng}`;
  if (pagetoken) {
    endpoint += `&pagetoken=${pagetoken}`;
  }

  const response = await fetch(endpoint);
  const data: NearbyPlacesResponse = await response.json();
  return data;
}

async function loadMorePlaces() {
  if (!currentRow.value || !nextPageToken.value) return; // safety check

  const [lat, lng] = currentRow.value.gps_position
    .split(",")
    .map((coord) => parseFloat(coord));

  const nearbyPlacesData = await getNearbyPlaces(lat, lng, nextPageToken.value);
  places.value = places.value.concat(nearbyPlacesData.results.results);
  nextPageToken.value = nearbyPlacesData.results.next_page_token || null;
}

async function updateRowWithPlaceData(rowId: string, place: any) {
  console.log(rowId);
  const endpoint = API_URL + `/place/${rowId}`;
  const response = await fetch(endpoint, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      newPlaceName: place.name,
      newPlaceAddress: place.vicinity,
      newPlaceGpsPosition:
        place.geometry.location.lat + "," + place.geometry.location.lat,
    }),
  });

  const data = await response.json();
  return data;
}

function selectPlace(place: any) {
  if (!currentRow.value) return; // safety check

  updateRowWithPlaceData(currentRow.value.id.toString(), place)
    .then((updatedData) => {
      // Show the success alert
      showSuccessAlert.value = true;

      // Hide the success alert after 3 seconds
      setTimeout(() => {
        showSuccessAlert.value = false;
      }, 3000);

      // Close the modal
      isOpen.value = false;

      // Refresh the payments table by fetching them again
      fetchPayments();
    })
    .catch((error) => {
      console.error("Error updating row:", error);
      // Handle error appropriately (maybe show a different alert or message)
    });
}
</script>
