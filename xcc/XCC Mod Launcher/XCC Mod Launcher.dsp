# Microsoft Developer Studio Project File - Name="XCC Mod Launcher" - Package Owner=<4>
# Microsoft Developer Studio Generated Build File, Format Version 6.00
# ** DO NOT EDIT **

# TARGTYPE "Win32 (x86) Application" 0x0101

CFG=XCC Mod Launcher - Win32 Debug
!MESSAGE This is not a valid makefile. To build this project using NMAKE,
!MESSAGE use the Export Makefile command and run
!MESSAGE 
!MESSAGE NMAKE /f "XCC Mod Launcher.mak".
!MESSAGE 
!MESSAGE You can specify a configuration when running NMAKE
!MESSAGE by defining the macro CFG on the command line. For example:
!MESSAGE 
!MESSAGE NMAKE /f "XCC Mod Launcher.mak" CFG="XCC Mod Launcher - Win32 Debug"
!MESSAGE 
!MESSAGE Possible choices for configuration are:
!MESSAGE 
!MESSAGE "XCC Mod Launcher - Win32 Release" (based on "Win32 (x86) Application")
!MESSAGE "XCC Mod Launcher - Win32 Debug" (based on "Win32 (x86) Application")
!MESSAGE 

# Begin Project
# PROP AllowPerConfigDependencies 0
# PROP Scc_ProjName ""
# PROP Scc_LocalPath ""
CPP=cl.exe
MTL=midl.exe
RSC=rc.exe

!IF  "$(CFG)" == "XCC Mod Launcher - Win32 Release"

# PROP BASE Use_MFC 6
# PROP BASE Use_Debug_Libraries 0
# PROP BASE Output_Dir "Release"
# PROP BASE Intermediate_Dir "Release"
# PROP BASE Target_Dir ""
# PROP Use_MFC 6
# PROP Use_Debug_Libraries 0
# PROP Output_Dir "Release"
# PROP Intermediate_Dir "Release"
# PROP Ignore_Export_Lib 0
# PROP Target_Dir ""
# ADD BASE CPP /nologo /MD /W3 /GX /O2 /D "WIN32" /D "NDEBUG" /D "_WINDOWS" /D "_AFXDLL" /Yu"stdafx.h" /FD /c
# ADD CPP /nologo /MD /W3 /GX /O2 /I "..\..\misc" /I "..\..\misc\libpng" /I "..\..\misc\zlib" /I "..\misc" /D "WIN32" /D "NDEBUG" /D "_WINDOWS" /D "_AFXDLL" /D "_MBCS" /Yu"stdafx.h" /FD /c
# ADD BASE MTL /nologo /D "NDEBUG" /mktyplib203 /win32
# ADD MTL /nologo /D "NDEBUG" /mktyplib203 /win32
# ADD BASE RSC /l 0x413 /d "NDEBUG" /d "_AFXDLL"
# ADD RSC /l 0x413 /d "NDEBUG" /d "_AFXDLL"
BSC32=bscmake.exe
# ADD BASE BSC32 /nologo
# ADD BSC32 /nologo
LINK32=link.exe
# ADD BASE LINK32 /nologo /subsystem:windows /machine:I386
# ADD LINK32 libjpeg.lib libpng.lib vfw32.lib ogg_static.lib vorbis_static.lib vorbisfile_static.lib /nologo /subsystem:windows /machine:I386

!ELSEIF  "$(CFG)" == "XCC Mod Launcher - Win32 Debug"

# PROP BASE Use_MFC 6
# PROP BASE Use_Debug_Libraries 1
# PROP BASE Output_Dir "Debug"
# PROP BASE Intermediate_Dir "Debug"
# PROP BASE Target_Dir ""
# PROP Use_MFC 6
# PROP Use_Debug_Libraries 1
# PROP Output_Dir "Debug"
# PROP Intermediate_Dir "Debug"
# PROP Ignore_Export_Lib 0
# PROP Target_Dir ""
# ADD BASE CPP /nologo /MDd /W3 /Gm /GX /ZI /Od /D "WIN32" /D "_DEBUG" /D "_WINDOWS" /D "_AFXDLL" /Yu"stdafx.h" /FD /GZ /c
# ADD CPP /nologo /MDd /W3 /Gm /GX /ZI /Od /I "..\..\misc" /I "..\..\misc\libpng" /I "..\..\misc\zlib" /I "..\misc" /D "WIN32" /D "_DEBUG" /D "_WINDOWS" /D "_AFXDLL" /D "_MBCS" /Yu"stdafx.h" /FD /GZ /c
# ADD BASE MTL /nologo /D "_DEBUG" /mktyplib203 /win32
# ADD MTL /nologo /D "_DEBUG" /mktyplib203 /win32
# ADD BASE RSC /l 0x413 /d "_DEBUG" /d "_AFXDLL"
# ADD RSC /l 0x413 /d "_DEBUG" /d "_AFXDLL"
BSC32=bscmake.exe
# ADD BASE BSC32 /nologo
# ADD BSC32 /nologo
LINK32=link.exe
# ADD BASE LINK32 /nologo /subsystem:windows /debug /machine:I386 /pdbtype:sept
# ADD LINK32 libjpeg.lib libpng.lib vfw32.lib ogg_static.lib vorbis_static.lib vorbisfile_static.lib /nologo /subsystem:windows /debug /machine:I386 /pdbtype:sept

!ENDIF 

# Begin Target

# Name "XCC Mod Launcher - Win32 Release"
# Name "XCC Mod Launcher - Win32 Debug"
# Begin Group "Source Files"

# PROP Default_Filter "cpp;c;cxx;rc;def;r;odl;idl;hpj;bat"
# Begin Source File

SOURCE="..\..\xhp\cgi-bin\misc\cgi.cpp"
# End Source File
# Begin Source File

SOURCE=.\download_dlg.cpp
# End Source File
# Begin Source File

SOURCE="..\..\xhp\cgi-bin\misc\html.cpp"
# End Source File
# Begin Source File

SOURCE=.\StdAfx.cpp
# ADD CPP /Yc"stdafx.h"
# End Source File
# Begin Source File

SOURCE="..\..\xhp\cgi-bin\misc\web_tools.cpp"
# End Source File
# Begin Source File

SOURCE=".\XCC Mod Launcher.cpp"
# End Source File
# Begin Source File

SOURCE=".\XCC Mod Launcher.rc"
# End Source File
# Begin Source File

SOURCE=".\XCC Mod LauncherDlg.cpp"
# End Source File
# Begin Source File

SOURCE=..\misc\xcc_mod.cpp
# End Source File
# End Group
# Begin Group "Header Files"

# PROP Default_Filter "h;hpp;hxx;hm;inl"
# Begin Source File

SOURCE=.\download_dlg.h
# End Source File
# Begin Source File

SOURCE=..\misc\mix_file_write.h
# End Source File
# Begin Source File

SOURCE=.\Resource.h
# End Source File
# Begin Source File

SOURCE=.\StdAfx.h
# End Source File
# Begin Source File

SOURCE=".\XCC Mod Launcher.h"
# End Source File
# Begin Source File

SOURCE=".\XCC Mod LauncherDlg.h"
# End Source File
# Begin Source File

SOURCE=..\misc\xcc_mod.h
# End Source File
# End Group
# Begin Group "Resource Files"

# PROP Default_Filter "ico;cur;bmp;dlg;rc2;rct;bin;rgs;gif;jpg;jpeg;jpe"
# Begin Source File

SOURCE=.\res\banner.bmp
# End Source File
# Begin Source File

SOURCE=.\res\banner.jpeg
# End Source File
# Begin Source File

SOURCE=.\res\binary1.bin
# End Source File
# Begin Source File

SOURCE=".\res\olaf-ss-2.bmp"
# End Source File
# Begin Source File

SOURCE=".\res\olaf-ss-3.jpg"
# End Source File
# Begin Source File

SOURCE=.\res\redstorm.bmp
# End Source File
# Begin Source File

SOURCE=".\res\XCC Mod Launcher.ico"
# End Source File
# Begin Source File

SOURCE=".\res\XCC Mod Launcher.rc2"
# End Source File
# End Group
# End Target
# End Project
