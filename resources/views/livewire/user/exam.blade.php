<?php 

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.exam')] class extends Component {
    
}; ?>

<div class="w-full h-dvh flex flex-col justify-start">
    <flux:container class="flex justify-between items-center mt-10">
        <img src="{{ asset('assets/primamarta_logo.png') }}" alt="primamarta_logo_nav" class="object-fill w-50 h-15">
        <img src="{{ asset('assets/smart_taruna_logo.png') }}" alt="primamarta_logo_nav" class="object-fill w-15">
    </flux:container>
    <flux:container class="my-10">
        <div class="grid grid-cols-3 gap-10">
            <div class="flex flex-col col-span-2 gap-5">
                <flux:container>
                    <p class="text-[#20327A] text-xl font-bold mb-1">Soal Nomor 1</p>
                </flux:container>
                <flux:container class="text-wrap h-[100vh] overflow-y-auto">
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatem, facere illo natus laborum vel quis libero provident excepturi, labore quaerat officiis eum laudantium! Quibusdam omnis incidunt fuga, sint repellendus quam accusantium. Optio nesciunt non hic culpa doloribus totam sed, cupiditate dolores unde necessitatibus? Aut omnis esse dignissimos ratione minima saepe culpa totam suscipit consequuntur. Voluptatibus tempore placeat delectus? Fugit odio saepe doloribus aut debitis dolor. Dolores obcaecati tempora ipsum? Amet distinctio, dolores voluptatem quae ipsam est tempore asperiores iure consequatur ex eos debitis aut consectetur fugit expedita porro quibusdam, eaque corporis quia voluptate? Voluptatibus veritatis quis cum, unde earum magni exercitationem dolor enim ullam omnis aliquam consectetur dolore voluptatum aspernatur non ducimus temporibus impedit eum quia. Expedita fuga, facilis explicabo numquam, ut ullam velit aspernatur iure adipisci asperiores alias earum labore quaerat sapiente eaque! Necessitatibus ea unde accusantium explicabo expedita ipsam qui, amet id quos iusto maiores! Itaque eos sunt nostrum neque laudantium, excepturi laborum optio nam fuga labore, consectetur voluptatibus dolor deleniti accusantium consequuntur, velit vitae tempore ipsum ipsam! Obcaecati minima ipsam iure placeat ea, repellat ducimus quidem omnis, fugiat quisquam commodi accusamus fuga? Iure magni ad velit omnis libero aperiam, ratione quos, ut quam recusandae, rem delectus labore mollitia beatae ab voluptas nihil dolor non quis perspiciatis expedita? Laboriosam pariatur excepturi eveniet corrupti beatae asperiores ex id dignissimos, distinctio adipisci ea, soluta alias voluptatum et quo ratione veritatis perspiciatis molestias libero atque voluptatem odit? Cumque totam, delectus tempora nostrum enim necessitatibus eos mollitia facilis repellendus animi, laboriosam aut, aperiam ipsum esse distinctio dignissimos ratione beatae quisquam? Harum ipsam ut, facilis ea tenetur nam perferendis eveniet. Consequuntur harum autem, architecto velit ut dolorem eum odio atque consequatur nobis saepe ex! Ratione libero placeat minus ad, amet a consequatur. Voluptatum quisquam dicta impedit veniam saepe delectus ad sit excepturi minus, accusantium perspiciatis sed facere ex assumenda reiciendis ipsa incidunt in dolores mollitia sunt tempore, sequi quod optio! Officiis atque voluptates enim dolore molestiae optio nam iure quisquam, sunt nobis, repudiandae animi cum fugit? Dolores vero possimus ipsam, repudiandae perferendis consequatur laudantium eos incidunt vel nam quis illum saepe quos eveniet nihil adipisci minima inventore neque autem doloremque impedit natus et. Voluptate omnis, libero et ducimus obcaecati magni illo, officia assumenda dignissimos quas beatae delectus possimus. Accusantium quae reprehenderit nam ad minus fuga iure deserunt perferendis autem vitae molestias ipsum impedit numquam labore dolorem, mollitia sunt, quam, atque voluptatibus quidem! Veritatis placeat eius, dolorem ab quod voluptatem sed reiciendis officia voluptate velit ratione, tempore, ipsum mollitia fugiat beatae doloremque voluptates? Veniam quisquam aperiam eaque facilis numquam quibusdam commodi non provident sunt doloribus? Tempore saepe accusantium eius est repellendus placeat, ratione iste cumque rerum quaerat, eveniet omnis nihil laudantium magnam, quae soluta quod ad pariatur. Quae corporis eveniet nisi ex, eius placeat voluptates incidunt sed ab veritatis pariatur voluptate repellendus nobis ut voluptatem expedita saepe magnam? Aliquid in quasi amet error deleniti eaque dolores rem a similique maiores commodi ducimus mollitia quisquam libero numquam ab, nihil omnis officia reprehenderit non rerum facere?</p>
                </flux:container>
                <flux:container>
                    <div class="flex flex-col space-y-2">
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="jawaban" value="A" class="form-radio">
                            <span>A. Pilihan Jawaban A</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="jawaban" value="B" class="form-radio">
                            <span>B. Pilihan Jawaban B</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="jawaban" value="C" class="form-radio">
                            <span>C. Pilihan Jawaban C</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="jawaban" value="D" class="form-radio">
                            <span>D. Pilihan Jawaban D</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="jawaban" value="E" class="form-radio">
                            <span>E. Pilihan Jawaban E</span>
                        </label>
                    </div>
                </flux:container>
            </div>
            <div class="flex flex-col gap-5">
                <flux:container>
                    <p class="text-[#20327A] text-xl font-bold">Daftar Soal</p>
                </flux:container>
                <flux:container>
                    <div class="grid grid-cols-5 gap-3 h-[55vh] overflow-y-auto">
                        @for ($i = 1; $i <= 100; $i++)<button class="flex items-center justify-center border rounded-md bg-gray-200 hover:bg-gray-300">
                            {{ $i }}
                            </button>
                            @endfor
                    </div>
                </flux:container>
                <flux:container>
                    {{-- timer --}}
                    <div class="flex justify-center items-center gap-2">
                        <flux:icon.clock class="size-5 text-[#20327A]"></flux:icon.clock>
                        <p class="text-[#20327A] text-xl font-bold">00:00:00</p>
                    </div>
                </flux:container>
            </div>
        </div>
    </flux:container>
    <flux:container class="h-20 flex justify-center items-center text-gray-400">
        <p class="text-xs">© Ngoding House {{ date('Y') }}</p>
    </flux:container>

</div>
