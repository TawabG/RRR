<?php

namespace App\Http\Controllers;

use App\Target;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Facades\Image;

class TargetController extends Controller
{
    /**
     * @author Casper Schobers, Geert Berkers
     *
     * Add a target with a base64 encoded image
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        $target = new Target();

        if (isset($request->name) && $request->name != "")
            $target->name = $request->name;
        else
            $target->name = "target";

        $target->qrcode = $request->qrCode;

        $imagePath = "targets/" . Carbon::now()->timestamp . "-" . $target->name . "-target.jpg";
        $imagePublicPath = public_path($imagePath);
        
        try {
            $base = base64_decode($request->image);
            $image = Image::make($base)->orientate();

            $MAX_HEIGHT = 1280;
            $CLOCKWISE = -90;

            $height = $image->height();
            $width = $image->width();

            $imageRatio = $width / $height;
            $imageIsHorizontal = ($imageRatio > 1);
            if ($imageIsHorizontal) {
                $image->rotate($CLOCKWISE);
                $height = $image->height();
                $width = $image->width();
            }

            $imageIsTooBig = $height > $MAX_HEIGHT;
            if($imageIsTooBig) {
                $scaleRatio = $MAX_HEIGHT / $height;
                $image->resize(($width * $scaleRatio), $MAX_HEIGHT);
            }

            $image->save($imagePublicPath);

        } catch (NotReadableException $e) {
            dump('base:' . $base . ' null: ');
            return response()->json(['error' => 'Wrong base64 encoded data only jpg is supported yet.'], 400);
        }

        $target->image = $imagePath;
        $target->game()->associate($request->gameId);
        $target->save();
        return response()->json(['targetId' => $target->id, 'targetName' => $target->name, 'targetUrl' => $target->image, 'targetQR' => $target->qrcode], 200);
    }

}
