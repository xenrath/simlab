@extends('front.main')

@section('content')
  <!-- ======= Why Us Section ======= -->
  <section id="why-us" class="why-us">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 d-flex align-items-stretch">
          <div class="content">
            <h3>Apa itu Laboratorium Terpadu?</h3>
            <p>Laboratorium Pendidikan Terpadu merupakan unit pelaksana teknis di bidang pengembangan pembelajaran dan
              layanan laboratorium yang berada di bawah dan bertanggung jawab kepada Rektor dan dikoordinasikan oleh
              Wakil Rektor Bidang Akademik.</p>
            <div class="text-center">
              <a href="#tentang" class="more-btn scrollto">Lebih Lengkap <i class="bx bx-chevron-right"></i></a>
            </div>
          </div>
        </div>
        <div class="col-lg-6 d-flex align-items-stretch">
          <div class="icon-boxes d-flex flex-column justify-content-center">
            <div class="row">
              <div class="col-xl-6 d-flex align-items-stretch">
                <div class="icon-box mt-4 mt-xl-0">
                  <i class="bx bx-receipt"></i>
                  <h4>Sertifikat Akreditasi</h4>
                  <p>Consequuntur sunt aut quasi enim aliquam quae harum pariatur laboris nisi ut aliquip</p>
                </div>
              </div>
              <div class="col-xl-6 d-flex align-items-stretch">
                <div class="icon-box mt-4 mt-xl-0">
                  <i class="bx bx-images"></i>
                  <h4>Ruang Praktik & Fasilitas</h4>
                  <p>Aut suscipit aut cum nemo deleniti aut omnis. Doloribus ut maiores omnis facere</p>
                </div>
              </div>
            </div>
          </div><!-- End .content-->
        </div>
      </div>
    </div>
  </section><!-- End Why Us Section -->

  <!-- ======= Section Tentang ======= -->
  <section id="tentang" class="tentang">
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-5 col-lg-6 video-box d-flex justify-content-center align-items-stretch position-relative">
          <a href="https://www.youtube.com/watch?v=TvXsOc1fFd0&ab_channel=UniversitasBhamadaSlawi"
            class="glightbox play-btn mb-4"></a>
        </div>
        <div
          class="col-xl-7 col-lg-6 icon-boxes d-flex flex-column align-items-stretch justify-content-center py-5 px-lg-5">
          <h3>Tentang Laboratorium Terpadu</h3>
          <p><strong>Laboratorium Pendidikan Terpadu</strong> mempunyai tugas melaksanakan layanan laboratorium untuk
            program
            pendidikan, penelitian, dan pengabdian kepada masyarakat secara terpadu.</p>
          <p>Dalam melaksanakan tugasnya, <strong>Laboratorium Pendidikan Terpadu</strong> menyelenggarakan fungsi :
          </p>
          <table>
            <tr>
              <td class="number">1.</td>
              <td class="description">
                Penyusunan rencana, program, dan anggaran
              </td>
            </tr>
            <tr>
              <td class="number">2.</td>
              <td class="description">
                Pelaksanaan layanan laboratorium untuk program pendidikan, penelitian, dan
                pengabdian kepada masyarakat
              </td>
            </tr>
            <tr>
              <td class="number">3.</td>
              <td class="description">
                Pemeliharaan dan perawatan laboratorium
              </td>
            </tr>
            <tr>
              <td class="number">4.</td>
              <td class="description">
                Pelaksanaan urusan tata usaha UPT
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <div class="counts">
      <div class="container">
        <div class="row">
          <div class="col-lg-3 col-md-6">
            <div class="count-box">
              <i class="fas fa-user-md"></i>
              <span data-purecounter-start="0" data-purecounter-end="{{ count($laborans) }}" data-purecounter-duration="1"
                class="purecounter"></span>
              <p>Laboran</p>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mt-5 mt-md-0">
            <div class="count-box">
              <i class="far fa-hospital"></i>
              <span data-purecounter-start="0" data-purecounter-end="{{ count($ruangs) }}" data-purecounter-duration="1"
                class="purecounter"></span>
              <p>Ruang Lab</p>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mt-5 mt-lg-0">
            <div class="count-box">
              <i class="fas fa-award"></i>
              <span data-purecounter-start="0" data-purecounter-end="{{ count($barangs) }}" data-purecounter-duration="1"
                class="purecounter"></span>
              <p>Peralatan</p>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mt-5 mt-lg-0">
            <div class="count-box">
              <i class="fas fa-flask"></i>
              <span data-purecounter-start="0" data-purecounter-end="{{ count($bahans) }}" data-purecounter-duration="1"
                class="purecounter"></span>
              <p>Bahan</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="testimonials">
      <div class="container">
        <div class="section-title">
          <h2>Siapa Saja?</h2>
          <p>Berikut adalah siapa saja orang yang ada di <strong>Laboratorium Terpadu</strong> meliputi ketua laboran
            beserta laboran dari masing-masing program studi.</p>
        </div>
        <div class="testimonials-slider swiper" data-aos="fade-up" data-aos-delay="100">
          <div class="swiper-wrapper">
            @foreach ($users as $user)
              @php
                $ruangs = \App\Models\Ruang::where([['laboran_id', $user->id], ['kode', '!=', '01'], ['kode', '!=', '02']])->get();
              @endphp
              <div class="swiper-slide">
                <div class="testimonial-wrap">
                  <div class="testimonial-item">
                    @if ($user->foto)
                      <img src="{{ asset('medilab/assets/img/testimonials/testimonials-1.jpg') }}" class="testimonial-img"
                        alt="">
                    @else
                      @if ($user->gender == 'L')
                        <img src="{{ asset('medilab/assets/img/testimonials/testimonials-5.jpg') }}"
                          class="testimonial-img" alt="">
                      @elseif ($user->gender == 'P')
                        <img src="{{ asset('medilab/assets/img/testimonials/testimonials-2.jpg') }}"
                          class="testimonial-img" alt="">
                      @endif
                    @endif
                    <h3>{{ $user->nama }}</h3>
                    <h4>{{ ucfirst($user->role) }}</h4>
                    <p class="mt-2">
                      @foreach ($ruangs as $ruang)
                        - {{ $ruang->nama }} <br>
                      @endforeach
                    </p>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </div>
  </section>
  <!-- End Section Tentang -->

  <!-- ======= Departments Section ======= -->
  <section id="departments" class="departments">
    <div class="container">
      <div class="section-title">
        <h2>Profil</h2>
        <p>Profil dari Laboratorium Terpadu Universitas Bhamada Slawi.</p>
      </div>
      <div class="row gy-4">
        <div class="col-lg-3">
          <ul class="nav nav-tabs flex-column">
            <li class="nav-item">
              <a class="nav-link active show" data-bs-toggle="tab" href="#visi-misi">Visi dan Misi</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="tab" href="#struktur-upt">Struktur UPT</a>
            </li>
          </ul>
        </div>
        <div class="col-lg-9">
          <div class="tab-content">
            <div class="tab-pane active show" id="visi-misi">
              <div class="row details">
                <h4>Visi</h4>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Hic sapiente, exercitationem delectus
                  officiis quisquam ut corrupti eveniet perferendis libero quam nostrum corporis molestias ducimus,
                  magnam ipsa doloremque consequuntur quod. Praesentium!</p>
                <h4>Misi</h4>
                <div>
                  <table>
                    <tr>
                      <td class="number">1.</td>
                      <td class="description">
                        Penyusunan rencana, program, dan anggaran
                      </td>
                    </tr>
                    <tr>
                      <td class="number">2.</td>
                      <td class="description">
                        Pelaksanaan layanan laboratorium untuk program pendidikan, penelitian, dan
                        pengabdian kepada masyarakat
                      </td>
                    </tr>
                    <tr>
                      <td class="number">3.</td>
                      <td class="description">
                        Pemeliharaan dan perawatan laboratorium
                      </td>
                    </tr>
                    <tr>
                      <td class="number">4.</td>
                      <td class="description">
                        Pelaksanaan urusan tata usaha UPT
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="struktur-upt">
              <div class="row details">
                <h4>Struktur UPT</h4>
                <div class="gallery-item">
                  <a href="{{ asset('medilab/assets/img/struktur.jpeg') }}" class="galelry-lightbox">
                    <img src="{{ asset('medilab/assets/img/struktur.jpeg') }}" alt="" class="img-fluid">
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
  <!-- End Departments Section -->

  <!-- ======= Berita Section ======= -->
  <section id="berita" class="berita">
    <div class="container">
      <div class="section-title">
        <h2>Berita Terkini</h2>
        <p>Berita terbaru mengenai laboratorium terpadu universitas bhamada slawi.</p>
      </div>
    </div>
    <div class="container-fluid">
      <div class="row g-0">
        @forelse ($beritas as $berita)
          <div class="col-lg-3 col-md-4">
            <a href="{{ url('berita/' . date('Y-m-d', strtotime($berita->created_at)) . '/' . $berita->slug) }}">
              <div class="gallery-item">
                <img src="{{ asset('storage/uploads/' . $berita->gambar) }}" alt="{{ $berita->judul }}">
                <div class="description">
                  <h5 class="m-3">{{ $berita->judul }}</h5>
                </div>
              </div>
            </a>
          </div>
        @empty
          <div class="col-12 border rounded shadow" style="height: 40vh; line-height: 40vh;">
            <p class="text-center">- Belum ada berita yang ditambahkan -</p>
          </div>
        @endforelse
      </div>
    </div>
  </section>
  <!-- End Gallery Section -->

  <!-- ======= Frequently Asked Questions Section ======= -->
  <section id="faq" class="faq section-bg">
    <div class="container">
      <div class="section-title">
        <h2>Frequently Asked Questions</h2>
        <p>Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint
          consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit
          in iste officiis commodi quidem hic quas.</p>
      </div>
      <div class="faq-list">
        <ul>
          <li data-aos="fade-up">
            <i class="bx bx-help-circle icon-help"></i> <a data-bs-toggle="collapse" class="collapse"
              data-bs-target="#faq-list-1">Non consectetur a erat nam at lectus urna duis? <i
                class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
            <div id="faq-list-1" class="collapse show" data-bs-parent=".faq-list">
              <p>
                Feugiat pretium nibh ipsum consequat. Tempus iaculis urna id volutpat lacus laoreet non curabitur
                gravida. Venenatis lectus magna fringilla urna porttitor rhoncus dolor purus non.
              </p>
            </div>
          </li>

          <li data-aos="fade-up" data-aos-delay="100">
            <i class="bx bx-help-circle icon-help"></i> <a data-bs-toggle="collapse" data-bs-target="#faq-list-2"
              class="collapsed">Feugiat scelerisque varius morbi enim nunc? <i
                class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
            <div id="faq-list-2" class="collapse" data-bs-parent=".faq-list">
              <p>
                Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet id
                donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque elit
                ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.
              </p>
            </div>
          </li>

          <li data-aos="fade-up" data-aos-delay="200">
            <i class="bx bx-help-circle icon-help"></i> <a data-bs-toggle="collapse" data-bs-target="#faq-list-3"
              class="collapsed">Dolor sit amet consectetur adipiscing elit? <i
                class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
            <div id="faq-list-3" class="collapse" data-bs-parent=".faq-list">
              <p>
                Eleifend mi in nulla posuere sollicitudin aliquam ultrices sagittis orci. Faucibus pulvinar elementum
                integer enim. Sem nulla pharetra diam sit amet nisl suscipit. Rutrum tellus pellentesque eu tincidunt.
                Lectus urna duis convallis convallis tellus. Urna molestie at elementum eu facilisis sed odio morbi
                quis
              </p>
            </div>
          </li>

          <li data-aos="fade-up" data-aos-delay="300">
            <i class="bx bx-help-circle icon-help"></i> <a data-bs-toggle="collapse" data-bs-target="#faq-list-4"
              class="collapsed">Tempus quam pellentesque nec nam aliquam sem et tortor consequat? <i
                class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
            <div id="faq-list-4" class="collapse" data-bs-parent=".faq-list">
              <p>
                Molestie a iaculis at erat pellentesque adipiscing commodo. Dignissim suspendisse in est ante in. Nunc
                vel risus commodo viverra maecenas accumsan. Sit amet nisl suscipit adipiscing bibendum est. Purus
                gravida quis blandit turpis cursus in.
              </p>
            </div>
          </li>

          <li data-aos="fade-up" data-aos-delay="400">
            <i class="bx bx-help-circle icon-help"></i> <a data-bs-toggle="collapse" data-bs-target="#faq-list-5"
              class="collapsed">Tortor vitae purus faucibus ornare. Varius vel pharetra vel turpis nunc eget lorem
              dolor? <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
            <div id="faq-list-5" class="collapse" data-bs-parent=".faq-list">
              <p>
                Laoreet sit amet cursus sit amet dictum sit amet justo. Mauris vitae ultricies leo integer malesuada
                nunc vel. Tincidunt eget nullam non nisi est sit amet. Turpis nunc eget lorem dolor sed. Ut venenatis
                tellus in metus vulputate eu scelerisque.
              </p>
            </div>
          </li>

        </ul>
      </div>

    </div>
  </section>
  <!-- End Frequently Asked Questions Section -->

  <!-- ======= Kontak Section ======= -->
  <section id="kontak" class="contact">
    <div class="container">
      <div class="section-title">
        <h2>Kontak</h2>
        <p>Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint
          consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit
          in iste officiis commodi quidem hic quas.</p>
      </div>
    </div>
    <div class="container">
      <div class="row mt-5">
        <div class="col-lg-7">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.1493300923175!2d109.11826881450068!3d-6.991686470414839!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6fbef42471658d%3A0x883656d1325ef066!2sBhamada%20Slawi!5e0!3m2!1sen!2sus!4v1674634706554!5m2!1sen!2sus"
            width="100%" height="350" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="col-lg-5 mt-5 mt-lg-0">
          <form action="forms/contact.php" method="post" role="form" class="php-email-form">
            <div class="row">
              <div class="col-md-6 form-group">
                <input type="text" name="name" class="form-control" id="name" placeholder="Your Name"
                  required>
              </div>
              <div class="col-md-6 form-group mt-3 mt-md-0">
                <input type="email" class="form-control" name="email" id="email" placeholder="Your Email"
                  required>
              </div>
            </div>
            <div class="form-group mt-3">
              <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject"
                required>
            </div>
            <div class="form-group mt-3">
              <textarea class="form-control" name="message" rows="5" placeholder="Message" required></textarea>
            </div>
            <div class="my-3">
              <div class="loading">Loading</div>
              <div class="error-message"></div>
              <div class="sent-message">Your message has been sent. Thank you!</div>
            </div>
            <div class="text-center"><button type="submit">Send Message</button></div>
          </form>
        </div>
      </div>
    </div>
  </section>
  <!-- End Kontak Section -->
@endsection
