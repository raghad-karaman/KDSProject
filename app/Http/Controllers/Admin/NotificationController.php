<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // LİSTE
    public function index()
    {
        $notifications = Notification::with('targetUser')->get();
        return view('admin.notifications.index', compact('notifications'));
    }

    // CREATE SAYFASI
    public function create()
    {
        $users = User::all();
        return view('admin.notifications.create', compact('users'));
    }

    // STORE — BİLDİRİM EKLEME
    public function store(Request $request)
    {
        $request->validate([
            'baslik' => 'required|string|max:255',
            'icerik' => 'required|string',
            'hedef_kullanici_id' => 'required|exists:users,id',
            'oncelik' => 'required|in:Düşük,Orta,Yüksek',
        ]);

        Notification::create([
            'baslik' => $request->baslik,
            'icerik' => $request->icerik,
            'hedef_kullanici_id' => $request->hedef_kullanici_id,
            'oncelik' => $request->oncelik,
            'durum' => 'Okunmadı',
            'olusturma_tarihi' => now(),
        ]);

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Bildirim başarıyla oluşturuldu!');
    }

    // EDIT SAYFASI
    public function edit($id)
    {
        $notification = Notification::findOrFail($id);
        $users = User::all();

        return view('admin.notifications.edit', compact('notification', 'users'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $notification = Notification::findOrFail($id);

        $request->validate([
            'baslik' => 'required|string|max:255',
            'icerik' => 'required|string',
            'hedef_kullanici_id' => 'required|exists:users,id',
            'oncelik' => 'required|in:Düşük,Orta,Yüksek',
            'durum' => 'required|in:Okundu,Okunmadı',
        ]);

        $notification->update($request->all());

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Bildirim başarıyla güncellendi!');
    }

    // DELETE
    public function destroy($id)
    {
        Notification::findOrFail($id)->delete();

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Bildirim silindi!');
    }
}
