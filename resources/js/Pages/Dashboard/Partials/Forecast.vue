<script setup>
import { ref, reactive, nextTick } from "vue";
import axios from "axios";
import JetSectionTitle from "@/Jetstream/SectionTitle.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetLabel from "@/Jetstream/Label.vue";

const regionCode = ref("ASI");
const countryCode = ref("PH");
const adminAreaCode = ref("BUL");
const locationKey = ref("");
const schedule = ref("daily");
const scheduleNumber = ref(1);
const forecast = ref("");
const forecastLoading = ref(false);

const list = reactive({
	regions: [],
	countries: [],
	adminAreas: [],
	cities: [],
});

const clearData = () => {

const forecast = ref({});
const forecastLoading = ref(true);
}

const getRegions = () => {
	const url = route("api.location.regions-list");
	axios
		.get(url)
		.then((response) => {
			const { data } = response;
			list.regions = data;
		})
		.catch((error) => {
			console.error(error);
		});
};

const getCountries = () => {
	const url = route("api.location.countries-list", {
		regionCode: regionCode.value,
	});
	axios
		.get(url)
		.then((response) => {
			const { data } = response;
			list.countries = data;
		})
		.catch((error) => {
			console.error(error);
		});
};

const getAdminAreas = () => {
	const url = route("api.location.admin-area-list", {
		countryCode: countryCode.value,
	});
	axios
		.get(url)
		.then((response) => {
			const { data } = response;
			list.adminAreas = data;
			console.log(data);
		})
		.catch((error) => {
			console.error(error);
		});
};

const getCities = () => {
	const url = route("api.location.cities-list", {
		countryCode: countryCode.value,
		adminCode: adminAreaCode.value,
	});

	axios
		.get(url)
		.then((response) => {
			const { data } = response;
			list.cities = data;
			console.log(data);
		})
		.catch((error) => {
			console.error(error);
		});
};

const getForecast = (_locationKey) => {
	if (locationKey.value == _locationKey) {
		return;
	}

	forecastLoading.value = true;
	locationKey.value = _locationKey;
	const params = {
		locationKey: _locationKey,
		schedule: schedule.value,
		hour: scheduleNumber.value,
		day: scheduleNumber.value,
	};

	const url = route("api.forecast-get", params);

	axios
		.get(url)
		.then((response) => {
			const { data } = response;
			forecast.value = data;
			forecastLoading.value = false;
		})
		.catch((error) => {
			forecastLoading.value = false;
			console.error(error);
		});
};

getRegions();
getCountries();
getAdminAreas();
getCities();
</script>

<template>
	<div>
		<JetSectionTitle>
			<template #title> Forecast </template>
			<template #description>
				Get forecast information for a specific location.
			</template>
		</JetSectionTitle>

		<div class="mt-5 md:mt-0 md:col-span-2">
			<div class="px-4 py-5 sm:p-6 bg-white shadow sm:rounded-lg">
				<div class="md:grid md:grid-cols-12 md:gap-6">
					<div class="col-span-3">
						<JetLabel for="regions" value="Region" />

						<select
							v-model="regionCode"
							@change="getCountries"
							class="
								border-gray-300
								focus:border-indigo-300
								focus:ring
								focus:ring-indigo-200
								focus:ring-opacity-50
								rounded-md
								shadow-sm
								block
								w-full
							"
						>
							<option value="">Select region</option>
							<option
								v-for="region in list.regions || []"
								:key="region.ID"
								:value="region.ID"
							>
								{{ region.LocalizedName }}
							</option>
						</select>
					</div>

					<div class="col-span-3">
						<JetLabel for="countries" value="Countries" />
						<select
							v-model="countryCode"
							@change="getAdminAreas"
							class="
								border-gray-300
								focus:border-indigo-300
								focus:ring
								focus:ring-indigo-200
								focus:ring-opacity-50
								rounded-md
								shadow-sm
								block
								w-full
							"
						>
							<option value="">Select country</option>
							<option
								v-for="country in list.countries || []"
								:key="country.ID"
								:value="country.ID"
							>
								{{ country.LocalizedName }}
							</option>
						</select>
					</div>

					<div class="col-span-6">
						<JetLabel for="admin-code" value="Administrative Area" />
						<select
							v-model="adminAreaCode"
							@change="getCities"
							class="
								border-gray-300
								focus:border-indigo-300
								focus:ring
								focus:ring-indigo-200
								focus:ring-opacity-50
								rounded-md
								shadow-sm
								block
								w-full
							"
						>
							<option value="">Select administrative area</option>
							<option
								v-for="adminArea in list.adminAreas || []"
								:key="adminArea.ID"
								:value="adminArea.ID"
							>
								{{ adminArea.LocalizedName }}
							</option>
						</select>
					</div>
				</div>

				<div class="mt-10 md:grid md:grid-cols-12 md:gap-6">
					<div class="col-span-3 overflow-auto">
						<ul class="space-y-6 lg:space-y-2 border-l border-slate-300">
							<li v-for="city in list.cities" :key="city.Key" :id="city.Key">
								<button
									@click="getForecast(city.Key)"
									:class="[
										locationKey == city.Key
											? 'block border-l pl-4 -ml-px border-transparent hover:border-slate-400  text-slate-700 hover:text-slate-900 font-semibold'
											: 'block border-l pl-4 -ml-px text-slate-300 border-current font-semibold ',
									]"
								>
									{{ city.LocalizedName }}
								</button>
							</li>
						</ul>
					</div>
					<div class="col-span-9">
                        <div v-if="!forecast && !forecastLoading">
                            Please select city!
                        </div>

                        <div v-if="forecastLoading">
                            Please wait!
                        </div>

						<div v-if="!forecastLoading && forecast">
							<div>
								<h1 class="font-bold text-lg">Head Line</h1>

								<table class="w-full text-sm text-left text-gray-700">
									<tr
										v-for="(value, key) in forecast.Headline"
										:key="key"
										class=""
									>
										<td class="py-2 px-4">{{ key }}</td>
										<td class="py-2 px-4">{{ value }}</td>
									</tr>
								</table>
							</div>

							<div class="mt-10">
								<h1 class="font-bold text-lg">Daily Forecasts</h1>
								<table class="w-full text-sm text-left text-gray-900">
									<tr>
										<th class="py-2 px-4">Date</th>
										<th class="py-2 px-4">Min. Temp.</th>
										<th class="py-2 px-4">Max. Temp</th>
										<th class="py-2 px-4">Day</th>
										<th class="py-2 px-4">Night</th>
									</tr>
									<tr
										v-for="dailyForecast in forecast.DailyForecasts"
										:key="dailyForecast.Date"
										class="text-gray-700"
									>
										<td class="py-2 px-4">
											{{ dailyForecast.Date.split("T")[0] }}
										</td>
										<td class="py-2 px-4">
											{{ dailyForecast.Temperature.Minimum.Value }} -
											{{ dailyForecast.Temperature.Minimum.Unit }}
										</td>
										<td class="py-2 px-4">
											{{ dailyForecast.Temperature.Maximum.Value }} -
											{{ dailyForecast.Temperature.Maximum.Unit }}
										</td>

										<td class="py-2 px-4">
											{{ dailyForecast.Day.IconPhrase }}
										</td>

										<td class="py-2 px-4">
											{{ dailyForecast.Night.IconPhrase }}
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
